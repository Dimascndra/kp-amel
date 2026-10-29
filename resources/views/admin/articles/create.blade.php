@extends('admin.layouts.app')

@section('title', 'Tulis Artikel Baru')
@section('page-title', 'Tulis Artikel & Edukasi Finansial')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow">
    <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
      <div>
        <h3 class="text-base font-bold text-slate-800">Editor Artikel Finansial</h3>
        <p class="text-xs text-slate-400">Tulis panduan literasi keuangan, tips UMKM, atau peringatan anti-fraud #AwasModus.</p>
      </div>
      <a href="{{ route('admin.articles.index') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
        &larr; Kembali
      </a>
    </div>

    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Artikel <span class="text-red-500">*</span></label>
          <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Mulai Dana Darurat dari Kebiasaan Sederhana Sehari-hari" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori / Tag Topik <span class="text-red-500">*</span></label>
          <input type="text" name="tag" value="{{ old('tag', 'Perencanaan Finansial') }}" required placeholder="Contoh: Panduan UMKM, #AwasModus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Warna Badge Kategori <span class="text-red-500">*</span></label>
          <select name="tag_color" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
            <option value="blue" {{ old('tag_color') === 'blue' ? 'selected' : '' }}>Biru (Finansial / Regulasi)</option>
            <option value="amber" {{ old('tag_color') === 'amber' ? 'selected' : '' }}>Amber (Panduan UMKM / Tips)</option>
            <option value="red" {{ old('tag_color') === 'red' ? 'selected' : '' }}>Merah (#AwasModus / Peringatan)</option>
            <option value="emerald" {{ old('tag_color') === 'emerald' ? 'selected' : '' }}>Hijau (Investasi / Tabungan)</option>
            <option value="purple" {{ old('tag_color') === 'purple' ? 'selected' : '' }}>Ungu (Umum)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Gambar Thumbnail (File)</label>
          <input type="file" name="image_file" accept="image/*" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          <p class="text-[10px] text-slate-400 mt-1">Maksimum 5MB (.jpg, .png, .webp)</p>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Atau Path Gambar Manual</label>
          <input type="text" name="image" value="{{ old('image', 'assets/img/perencanaan-keluarga.jpg') }}" placeholder="assets/img/nama-gambar.jpg" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estimasi Waktu Baca</label>
          <input type="text" name="read_time" value="{{ old('read_time', '4 menit baca') }}" placeholder="Contoh: 3 menit baca" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Penulis / Tim Redaksi</label>
          <input type="text" name="author" value="{{ old('author', 'Tim Redaksi Bank Majalengka') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Terbit <span class="text-red-500">*</span></label>
          <input type="date" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ringkasan Artikel (Excerpt) <span class="text-red-500">*</span></label>
          <textarea name="excerpt" rows="2" required placeholder="Satu atau dua kalimat pengantar untuk ditampilkan pada card berita..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('excerpt') }}</textarea>
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konten Artikel Lengkap (HTML Didukung) <span class="text-red-500">*</span></label>
          <textarea name="content" rows="10" required placeholder="Tuliskan isi artikel lengkap di sini. Anda dapat menggunakan tag paragraph <p>, subjudul <h4>, dan list <ul>/<li>." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('content') }}</textarea>
        </div>

        <div class="flex items-center pt-2">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-bpr-blue">
            <span class="text-xs font-bold text-slate-700">Publikasikan Langsung ke Website</span>
          </label>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow transition">
          Simpan Artikel
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
