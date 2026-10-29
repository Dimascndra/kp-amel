<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = LoanApplication::query();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('reference_code', 'like', "%{$s}%")
                  ->orWhere('hp', 'like', "%{$s}%");
            });
        }

        $loans = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => LoanApplication::count(),
            'pending' => LoanApplication::where('status', 'pending')->count(),
            'contacted' => LoanApplication::where('status', 'contacted')->count(),
            'surveyed' => LoanApplication::where('status', 'surveyed')->count(),
            'approved' => LoanApplication::where('status', 'approved')->count(),
            'rejected' => LoanApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.loans.index', compact('loans', 'statusCounts'));
    }

    public function show(LoanApplication $loan): View
    {
        return view('admin.loans.show', compact('loan'));
    }

    public function updateStatus(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,contacted,surveyed,approved,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $loan->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', "Status pengajuan pinjaman '{$loan->reference_code}' berhasil diperbarui ke: " . strtoupper($validated['status']));
    }

    public function destroy(LoanApplication $loan): RedirectResponse
    {
        $ref = $loan->reference_code;
        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', "Data pengajuan pinjaman '{$ref}' telah dihapus.");
    }
}
