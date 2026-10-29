@extends('admin.layouts.app')

@section('title', 'Pengaturan Profil BPR & Kontak')
@section('page-title', 'Konfigurasi Profil, Kontak & Regulasi BPR')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow">
    <div class="pb-6 border-b border-slate-100 mb-6">
      <h3 class="text-base font-bold text-slate-800">Pengaturan Profil Perumda BPR Majalengka</h3>
      <p class="text-xs text-slate-400">Nilai-nilai ini ditampilkan secara langsung pada website company profile publik.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
      @csrf
      @method('PUT')

      <!-- Seksi 1: Identitas & Hero -->
      <div class="space-y-4">
        <h4 class="text-xs font-extrabold uppercase tracking-wider text-bpr-navy flex items-center gap-2 pb-2 border-b border-slate-100">
          <span class="w-2 h-2 rounded-full bg-bpr-navy"></span>
          1. Identitas Badan Usaha & Slogan Utama
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Badan Usaha</label>
            <input type="text" name="company_name" value="{{ $rawSettings['company_name'] ?? 'Perumda BPR Majalengka' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Aplikasi Web</label>
            <input type="text" name="app_name" value="{{ $rawSettings['app_name'] ?? 'Bank Majalengka' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tagline Perusahaan (Slogan BUMD)</label>
            <input type="text" name="company_tagline" value="{{ $rawSettings['company_tagline'] ?? 'Mitra Kerja Membangun Ekonomi Rakyat' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Headline Hero Utama</label>
            <input type="text" name="hero_headline" value="{{ $rawSettings['hero_headline'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Subheadline Hero</label>
            <textarea name="hero_subheadline" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['hero_subheadline'] ?? '' }}</textarea>
          </div>
        </div>
      </div>

      <!-- Seksi 2: Kontak & Kantor Pusat -->
      <div class="space-y-4">
        <h4 class="text-xs font-extrabold uppercase tracking-wider text-emerald-700 flex items-center gap-2 pb-2 border-b border-slate-100">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
          2. Alamat Pusat, Kontak & Jam Kerja
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Kantor Pusat Operasional</label>
            <input type="text" name="address_central" value="{{ $rawSettings['address_central'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Telepon Kantor Pusat</label>
            <input type="text" name="phone_central" value="{{ $rawSettings['phone_central'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">WhatsApp Resmi BPR</label>
            <input type="text" name="whatsapp_official" value="{{ $rawSettings['whatsapp_official'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Resmi</label>
            <input type="email" name="email_official" value="{{ $rawSettings['email_official'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Operasional</label>
            <input type="text" name="operational_hours" value="{{ $rawSettings['operational_hours'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
        </div>
      </div>

      <!-- Seksi 3: Profil & Sejarah Singkat -->
      <div class="space-y-4">
        <h4 class="text-xs font-extrabold uppercase tracking-wider text-amber-700 flex items-center gap-2 pb-2 border-b border-slate-100">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          3. Profil BPR, Visi, Misi & Sejarah Singkat
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Profil Tentang Kami</label>
            <input type="text" name="about_title" value="{{ $rawSettings['about_title'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Lengkap Tentang Kami</label>
            <textarea name="about_description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['about_description'] ?? '' }}</textarea>
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Sejarah Merger 8 BPR Majalengka</label>
            <textarea name="sejarah_singkat" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['sejarah_singkat'] ?? '' }}</textarea>
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Visi Perusahaan</label>
            <textarea name="vision" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['vision'] ?? '' }}</textarea>
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Misi Perusahaan</label>
            <textarea name="mission" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['mission'] ?? '' }}</textarea>
          </div>
        </div>
      </div>

      <!-- Seksi 4: Pernyataan Regulasi & Keamanan -->
      <div class="space-y-4">
        <h4 class="text-xs font-extrabold uppercase tracking-wider text-purple-700 flex items-center gap-2 pb-2 border-b border-slate-100">
          <span class="w-2 h-2 rounded-full bg-purple-600"></span>
          4. Regulasi LPS, Pengawasan OJK & Whistleblowing
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Pernyataan Penjaminan LPS</label>
            <textarea name="lps_guarantee_text" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['lps_guarantee_text'] ?? '' }}</textarea>
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Pernyataan Pengawasan OJK</label>
            <textarea name="ojk_supervision_text" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['ojk_supervision_text'] ?? '' }}</textarea>
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Pita Pemberitahuan Anti-Fraud (#AwasModus)</label>
            <textarea name="anti_fraud_notice" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ $rawSettings['anti_fraud_notice'] ?? '' }}</textarea>
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email WBS (Whistleblowing System)</label>
            <input type="email" name="wbs_email" value="{{ $rawSettings['wbs_email'] ?? '' }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
          </div>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
        <button type="submit" class="px-8 py-3 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow transition">
          Simpan Semua Pengaturan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
