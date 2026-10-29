@extends('admin.layouts.app')

@section('title', 'Edit Laporan: ' . $report->title)
@section('page-title', 'Edit Dokumen Publikasi')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow">
    <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
      <div>
        <h3 class="text-base font-bold text-slate-800">Edit Dokumen: {{ $report->title }}</h3>
        <p class="text-xs text-slate-400">ID: {{ $report->id }} • Kategori: {{ $report->category }}</p>
      </div>
      <a href="{{ route('admin.reports.index') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
        &larr; Kembali
      </a>
    </div>

    <form method="POST" action="{{ route('admin.reports.update', $report) }}" enctype="multipart/form-data" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Dokumen Publikasi <span class="text-red-500">*</span></label>
          <input type="text" name="title" value="{{ old('title', $report->title) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Dokumen <span class="text-red-500">*</span></label>
          <input type="text" name="category" value="{{ old('category', $report->category) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Warna Label Kategori <span class="text-red-500">*</span></label>
          <select name="category_color" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
            <option value="blue" {{ old('category_color', $report->category_color) === 'blue' ? 'selected' : '' }}>Biru (Triwulan Keuangan)</option>
            <option value="emerald" {{ old('category_color', $report->category_color) === 'emerald' ? 'selected' : '' }}>Hijau (Tata Kelola / GCG)</option>
            <option value="amber" {{ old('category_color', $report->category_color) === 'amber' ? 'selected' : '' }}>Amber (Audit Tahunan KAP)</option>
            <option value="purple" {{ old('category_color', $report->category_color) === 'purple' ? 'selected' : '' }}>Ungu (Keuangan Berkelanjutan RAKB)</option>
            <option value="slate" {{ old('category_color', $report->category_color) === 'slate' ? 'selected' : '' }}>Abu-abu (Pengawasan Internal)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Periode Pelaporan</label>
          <input type="text" name="period" value="{{ old('period', $report->period) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Berkas PDF Baru (Opsional)</label>
          <input type="file" name="file" accept=".pdf" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti file.</p>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tautan / Path Berkas PDF Saat Ini</label>
          <input type="text" name="file_path" value="{{ old('file_path', $report->file_path) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ukuran File</label>
          <input type="text" name="file_size" value="{{ old('file_size', $report->file_size) }}" placeholder="1.8 MB" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Ringkas</label>
          <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('description', $report->description) }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil (Sort Order)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $report->sort_order) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="flex items-center pt-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $report->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-bpr-blue">
            <span class="text-xs font-bold text-slate-700">Tampilkan di Publikasi Website</span>
          </label>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow transition">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
