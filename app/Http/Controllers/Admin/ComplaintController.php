<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ComplaintStatusUpdatedMail;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $query = Complaint::query();

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Filter by category
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('kategori', $request->input('category'));
        }

        // Filter by fraud risk
        if ($request->filled('risk') && $request->input('risk') !== 'all') {
            $query->where('fraud_risk_level', $request->input('risk'));
        }

        // Search query
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('ticket_code', 'like', "%{$q}%")
                  ->orWhere('nama', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('telepon', 'like', "%{$q}%")
                  ->orWhere('judul', 'like', "%{$q}%")
                  ->orWhere('nomor_rekening', 'like', "%{$q}%");
            });
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => Complaint::count(),
            'diproses' => Complaint::where('status', 'diproses')->count(),
            'ditindaklanjuti' => Complaint::where('status', 'ditindaklanjuti')->count(),
            'selesai' => Complaint::where('status', 'selesai')->count(),
            'high_risk' => Complaint::where('fraud_risk_level', 'high')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    public function show(Complaint $complaint): View
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:diproses,ditindaklanjuti,selesai,ditolak'],
            'catatan_petugas' => ['nullable', 'string', 'max:3000'],
            'send_email' => ['nullable', 'boolean'],
        ]);

        $previousStatus = $complaint->status;
        $newStatus = $validated['status'];

        $complaint->status = $newStatus;
        $complaint->catatan_petugas = $validated['catatan_petugas'] ?? $complaint->catatan_petugas;
        $complaint->ditangani_oleh = auth()->user()->name ?? 'Petugas Layanan Nasabah';

        if ($newStatus === 'ditindaklanjuti' && !$complaint->tanggal_ditindaklanjuti) {
            $complaint->tanggal_ditindaklanjuti = now();
        }

        if ($newStatus === 'selesai' && !$complaint->tanggal_selesai) {
            $complaint->tanggal_selesai = now();
        }

        $complaint->save();

        // Optionally send email notification to complainant
        $emailNotice = '';
        if ($request->boolean('send_email', true) && $previousStatus !== $newStatus) {
            try {
                Mail::to($complaint->email)->send(new ComplaintStatusUpdatedMail($complaint, $previousStatus));
                $emailNotice = ' dan email notifikasi berhasil dikirimkan ke nasabah';
            } catch (\Throwable $e) {
                Log::error("Failed to send status update email to {$complaint->email}: " . $e->getMessage());
                $emailNotice = ' (namun pengiriman email notifikasi mengalami kendala)';
            }
        }

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', "Status pengaduan [{$complaint->ticket_code}] berhasil diperbarui menjadi {$complaint->status_label}{$emailNotice}.");
    }

    public function destroy(Complaint $complaint): RedirectResponse
    {
        if ($complaint->lampiran && Storage::disk('public')->exists($complaint->lampiran)) {
            Storage::disk('public')->delete($complaint->lampiran);
        }

        $code = $complaint->ticket_code;
        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', "Data pengaduan [{$code}] berhasil dihapus dari sistem.");
    }
}
