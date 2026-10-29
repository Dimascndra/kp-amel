@extends('admin.layouts.app')

@section('title', 'Tambah Produk Baru')
@section('page-title', 'Tambah Produk Perbankan')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow">
    <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
      <div>
        <h3 class="text-base font-bold text-slate-800">Formulir Master Produk</h3>
        <p class="text-xs text-slate-400">Isi data produk perbankan Bank Majalengka secara lengkap.</p>
      </div>
      <a href="{{ route('admin.products.index') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
        &larr; Kembali
      </a>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Tabungan Hari Raya (TAHARA)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Produk <span class="text-red-500">*</span></label>
          <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: TAB-01 atau KRD-05" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Produk <span class="text-red-500">*</span></label>
          <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
            <option value="tabungan" {{ old('category') === 'tabungan' ? 'selected' : '' }}>Tabungan</option>
            <option value="deposito" {{ old('category') === 'deposito' ? 'selected' : '' }}>Deposito</option>
            <option value="kredit" {{ old('category') === 'kredit' ? 'selected' : '' }}>Kredit (Pembiayaan)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Badge / Highlight</label>
          <input type="text" name="badge" value="{{ old('badge') }}" placeholder="Contoh: Favorit Keluarga, LPS 2 Miliar" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slogan Singkat Produk</label>
          <input type="text" name="slogan" value="{{ old('slogan') }}" placeholder="Persiapan menyambut hari raya Idul Fitri yang tenang dan berkah." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap Produk</label>
          <textarea name="short_desc" rows="3" placeholder="Jelaskan manfaat produk bagi nasabah..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('short_desc') }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Gambar Produk (File)</label>
          <input type="file" name="image_file" accept="image/*" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          <p class="text-[10px] text-slate-400 mt-1">Maksimum 5MB (.png, .jpg)</p>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Atau Path Gambar Manual</label>
          <input type="text" name="image" value="{{ old('image', 'assets/img/produk/tahara.png') }}" placeholder="assets/img/produk/nama-file.png" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Hero Modal Banner (File)</label>
          <input type="file" name="hero_image_file" accept="image/*" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Atau Hero Image Manual</label>
          <input type="text" name="hero_image" value="{{ old('hero_image', 'assets/img/hero-batik.jpg') }}" placeholder="assets/img/hero-batik.jpg" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teks Tombol Aksi (CTA)</label>
          <input type="text" name="cta_text" value="{{ old('cta_text', 'Buka Rekening') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Tombol CTA <span class="text-red-500">*</span></label>
          <select name="cta_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
            <option value="whatsapp" {{ old('cta_type') === 'whatsapp' ? 'selected' : '' }}>Hubungi WhatsApp Resmi</option>
            <option value="form_pengajuan" {{ old('cta_type') === 'form_pengajuan' ? 'selected' : '' }}>Buka Form Pengajuan Online</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
            Ketentuan Produk (Tulis 1 poin per baris)
          </label>
          <textarea name="ketentuan" rows="4" placeholder="Jangka waktu penempatan 10 s/d 12 bulan&#10;Setoran bulanan terjangkau mulai Rp 50.000,-&#10;Dijamin sepenuhnya oleh LPS" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('ketentuan') }}</textarea>
          <span class="text-[11px] text-slate-400">Setiap baris baru akan otomatis menjadi poin bullet list di modal produk.</span>
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
            Persyaratan Berkas (Tulis 1 poin per baris)
          </label>
          <textarea name="syarat" rows="4" placeholder="Fotokopi KTP / Identitas pemohon&#10;Mengisi dan menandatangani formulir&#10;Menyetorkan setoran awal" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('syarat') }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil (Sort Order)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="flex items-center pt-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-bpr-blue">
            <span class="text-xs font-bold text-slate-700">Status Produk Aktif (Tampil di Website)</span>
          </label>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow transition">
          Simpan Produk Baru
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
