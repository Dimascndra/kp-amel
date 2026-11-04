@extends('admin.layouts.app')

@section('title', 'Detail Pengaduan: ' . $complaint->ticket_code)
@section('page-title', 'Detail & Penanganan Pengaduan Nasabah')

@section('content')
<div class="space-y-6">

  <!-- Top Action Bar -->
  <div class="flex items-center justify-between gap-4">
    <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-bpr-navy transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      <span>Kembali ke Daftar Pengaduan</span>
    </a>

    <div class="flex items-center gap-2">
      <span class="text-xs text-slate-500 font-medium">Status Saat Ini:</span>
      @if($complaint->status === 'selesai')
        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
          ✓ Selesai
        </span>
      @elseif($complaint->status === 'ditindaklanjuti')
        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-blue-100 text-blue-800 border border-blue-300">
          ⚙ Ditindaklanjuti
        </span>
      @else
        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-amber-100 text-amber-800 border border-amber-300">
          ⏳ Diproses
        </span>
      @endif
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Complaint Details & Anti-Fraud Inspection (2 cols) -->
    <div class="lg:col-span-2 space-y-6">

      <!-- Main Complaint Card -->
      <div class="bg-white rounded-2xl card-shadow p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
          <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">KODE PENGADUAN</span>
            <h3 class="text-xl font-extrabold text-bpr-navy font-mono">{{ $complaint->ticket_code }}</h3>
          </div>
          <div class="text-right">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">WAKTU LAPOR</span>
            <p class="text-xs font-bold text-slate-700">{{ $complaint->created_at->format('d F Y, H:i') }} WIB</p>
          </div>
        </div>

        <!-- Complainant Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50/70 p-4 rounded-xl border border-slate-100">
          <div>
            <span class="text-[11px] text-slate-400 uppercase font-bold">Nama Lengkap Pelapor</span>
            <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $complaint->nama }}</p>
          </div>
          <div>
            <span class="text-[11px] text-slate-400 uppercase font-bold">Alamat Email</span>
            <p class="text-sm font-semibold text-bpr-blue mt-0.5">{{ $complaint->email }}</p>
          </div>
          <div>
            <span class="text-[11px] text-slate-400 uppercase font-bold">Nomor WhatsApp / Kontak</span>
            <p class="text-sm font-mono font-bold text-slate-800 mt-0.5">
              <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $complaint->telepon)) }}" target="_blank" class="hover:underline text-emerald-700 inline-flex items-center gap-1">
                <span>{{ $complaint->telepon }}</span>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
              </a>
            </p>
          </div>
          <div>
            <span class="text-[11px] text-slate-400 uppercase font-bold">Nomor Rekening Bank (Jika Ada)</span>
            <p class="text-sm font-mono font-bold text-slate-800 mt-0.5">{{ $complaint->nomor_rekening ?: '-' }}</p>
          </div>
        </div>

        <!-- Subject & Narrative -->
        <div>
          <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold uppercase bg-blue-50 text-bpr-navy mb-2">
            Kategori: {{ ucfirst($complaint->kategori) }}
          </span>
          <h4 class="text-lg font-extrabold text-slate-900 leading-snug">{{ $complaint->judul }}</h4>
          <div class="mt-3 p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
            {{ $complaint->isi_pengaduan }}
          </div>
        </div>

        <!-- Attachment -->
        @if($complaint->lampiran)
          <div class="border-t border-slate-100 pt-4">
            <h5 class="text-xs font-bold text-slate-700 uppercase mb-2">Bukti Lampiran Pengaduan:</h5>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200">
              <svg class="w-6 h-6 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate">{{ basename($complaint->lampiran) }}</p>
                <p class="text-[10px] text-slate-400">File bukti lampiran nasabah</p>
              </div>
              <a href="{{ asset('storage/' . $complaint->lampiran) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-bpr-navy hover:bg-bpr-blue text-white text-xs font-bold transition">
                Buka File
              </a>
            </div>
          </div>
        @endif

      </div>

      <!-- Anti-Fraud Security Inspector Card -->
      <div class="bg-white rounded-2xl card-shadow p-6 space-y-4 border-l-4 {{ $complaint->fraud_risk_level === 'high' ? 'border-red-500' : ($complaint->fraud_risk_level === 'medium' ? 'border-amber-500' : 'border-emerald-500') }}">
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 {{ $complaint->fraud_risk_level === 'high' ? 'text-red-500' : 'text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <h4 class="font-extrabold text-sm text-slate-900">Audit Trail & Verifikasi Anti-Fraud</h4>
          </div>
          <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $complaint->fraud_risk_level === 'high' ? 'bg-red-100 text-red-800' : ($complaint->fraud_risk_level === 'medium' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
            Risk Level: {{ strtoupper($complaint->fraud_risk_level) }}
          </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-slate-50 p-3.5 rounded-xl">
          <div>
            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Alamat IP Pelapor</span>
            <span class="font-mono font-bold text-slate-800">{{ $complaint->ip_address ?: 'Tidak tercatat' }}</span>
          </div>
          <div>
            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Verifikasi CAPTCHA</span>
            <span class="font-bold text-emerald-700">Lolos Validasi Keamanan ✓</span>
          </div>
          <div class="sm:col-span-2">
            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Browser & User-Agent</span>
            <span class="font-mono text-[11px] text-slate-600 break-all">{{ $complaint->user_agent ?: '-' }}</span>
          </div>
          <div class="sm:col-span-2">
            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Catatan Analisis Risiko</span>
            <span class="text-slate-700">{{ $complaint->fraud_notes ?: 'Pemeriksaan normal. Tidak terdeteksi pola spam, injeksi XSS, atau tautan phishing.' }}</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Right Column: Status Update & Action Panel (1 col) -->
    <div class="space-y-6">

      <!-- Form Update Status -->
      <div class="bg-white rounded-2xl card-shadow p-6 space-y-5">
        <h4 class="font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
          <svg class="w-4 h-4 text-bpr-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Perbarui Status & Tindak Lanjut
        </h4>

        <form method="POST" action="{{ route('admin.complaints.updateStatus', $complaint) }}" class="space-y-4">
          @csrf
          @method('PUT')

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
              Status Penanganan <span class="text-red-500">*</span>
            </label>
            <select name="status" required class="w-full py-2.5 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
              <option value="diproses" {{ $complaint->status === 'diproses' ? 'selected' : '' }}>Diproses (Sedang Diteliti)</option>
              <option value="ditindaklanjuti" {{ $complaint->status === 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti (Investigasi Unit Terkait)</option>
              <option value="selesai" {{ $complaint->status === 'selesai' ? 'selected' : '' }}>Selesai (Solusi Tuntas Ditetapkan)</option>
              <option value="ditolak" {{ $complaint->status === 'ditolak' ? 'selected' : '' }}>Ditolak (Data Tidak Valid)</option>
            </select>
            <p class="text-[10px] text-slate-400 mt-1">Status akan dapat dicek langsung oleh nasabah di website menggunakan kode unik.</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
              Tanggapan Resmi / Catatan Tindak Lanjut
            </label>
            <textarea name="catatan_petugas" rows="5" placeholder="Tuliskan hasil investigasi, tindak lanjut kantor kas, atau solusi resmi yang diberikan kepada nasabah..." class="w-full py-2.5 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-bpr-blue leading-relaxed">{{ old('catatan_petugas', $complaint->catatan_petugas) }}</textarea>
            <p class="text-[10px] text-slate-400 mt-0.5">Catatan ini akan tampil di riwayat cek status nasabah dan dicantumkan pada email notifikasi.</p>
          </div>

          <div class="p-3 bg-blue-50/70 rounded-xl border border-blue-100 flex items-start gap-2.5">
            <input type="checkbox" name="send_email" id="send_email" value="1" checked class="mt-0.5 rounded text-bpr-blue focus:ring-bpr-blue">
            <label for="send_email" class="text-xs text-slate-700 leading-snug">
              <strong>Kirim Email Notifikasi Otomatis</strong> ke pelapor ({{ $complaint->email }}) memberitahukan perubahan status ini.
            </label>
          </div>

          <button type="submit" class="w-full py-3 bg-bpr-navy hover:bg-bpr-blue text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all">
            Simpan & Perbarui Status
          </button>
        </form>
      </div>

      <!-- Historical Progress Card -->
      <div class="bg-white rounded-2xl card-shadow p-6 space-y-3 text-xs">
        <h4 class="font-extrabold text-xs text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
          Riwayat Penanganan
        </h4>
        <div class="space-y-2.5 text-slate-600">
          <div class="flex items-center justify-between">
            <span class="text-slate-400">Petugas Terakhir:</span>
            <span class="font-bold text-slate-800">{{ $complaint->ditangani_oleh ?: '-' }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-400">Tgl Ditindaklanjuti:</span>
            <span class="font-medium text-slate-800">{{ $complaint->tanggal_ditindaklanjuti ? $complaint->tanggal_ditindaklanjuti->format('d/m/Y H:i') : '-' }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-400">Tgl Selesai:</span>
            <span class="font-medium text-slate-800">{{ $complaint->tanggal_selesai ? $complaint->tanggal_selesai->format('d/m/Y H:i') : '-' }}</span>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>
@endsection
