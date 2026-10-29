<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicationReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = PublicationReport::query();

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%")
                  ->orWhere('period', 'like', "%{$s}%");
            });
        }

        $reports = $query->orderBy('sort_order')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function create(): View
    {
        return view('admin.reports.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'category_color' => ['required', 'in:blue,emerald,amber,purple,slate'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'file_size' => ['nullable', 'string', 'max:50'],
            'period' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            $destPath = public_path('uploads/reports');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $uploadedFile->move($destPath, $filename);
            $validated['file_path'] = '/uploads/reports/' . $filename;

            $bytes = file_exists($destPath . '/' . $filename) ? filesize($destPath . '/' . $filename) : 0;
            if ($bytes >= 1048576) {
                $validated['file_size'] = number_format($bytes / 1048576, 1) . ' MB';
            } else {
                $validated['file_size'] = number_format(max(1, $bytes / 1024), 0) . ' KB';
            }
        }

        $validated['file_size'] = $validated['file_size'] ?? '1.5 MB';
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        PublicationReport::create($validated);

        return redirect()->route('admin.reports.index')
            ->with('success', "Laporan Publikasi '{$validated['title']}' berhasil ditambahkan!");
    }

    public function edit(PublicationReport $report): View
    {
        return view('admin.reports.edit', compact('report'));
    }

    public function update(Request $request, PublicationReport $report): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'category_color' => ['required', 'in:blue,emerald,amber,purple,slate'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'file_size' => ['nullable', 'string', 'max:50'],
            'period' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            $destPath = public_path('uploads/reports');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $uploadedFile->move($destPath, $filename);
            $validated['file_path'] = '/uploads/reports/' . $filename;

            $bytes = file_exists($destPath . '/' . $filename) ? filesize($destPath . '/' . $filename) : 0;
            if ($bytes >= 1048576) {
                $validated['file_size'] = number_format($bytes / 1048576, 1) . ' MB';
            } else {
                $validated['file_size'] = number_format(max(1, $bytes / 1024), 0) . ' KB';
            }
        } elseif (empty($validated['file_size'])) {
            $validated['file_size'] = $report->file_size ?: '1.5 MB';
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $report->update($validated);

        return redirect()->route('admin.reports.index')
            ->with('success', "Laporan Publikasi '{$report->title}' berhasil diperbarui!");
    }

    public function destroy(PublicationReport $report): RedirectResponse
    {
        $title = $report->title;
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', "Laporan Publikasi '{$title}' telah dihapus.");
    }

    public function toggleStatus(PublicationReport $report): RedirectResponse
    {
        $report->update(['is_active' => !$report->is_active]);

        $status = $report->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status laporan berhasil {$status}.");
    }
}
