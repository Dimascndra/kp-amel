@extends('admin.layouts.app')

@section('title', 'Tinjau Berkas Pengajuan: ' . $loan->reference_code)
@section('page-title', 'Detail Calon Debitur & Pengajuan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

  <!-- Header Card -->
  <div class="bg-white rounded-2xl p-6 card-shadow flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-3">
        <span class="font-mono text-xs font-bold text-slate-400">NOMOR TIKET</span>
        <span class="font-mono text-base font-extrabold text-bpr-navy">{{ $loan->reference_code }}</span>
        @php
          $statusStyles = [
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'contacted' => 'bg-blue-50 text-blue-700 border-blue-200',
            'surveyed' => 'bg-purple-50 text-purple-700 border-purple-200',
            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-red-50 text-red-700 border-red-200',
          ];
          $badgeStyle = $statusStyles[$loan->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
        @endphp
        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeStyle }} uppercase">
          {{ $loan->status }}
        </span>
      </div>
      <p class="text-xs text-slate-500 mt-1">
        Masuk pada {{ $loan->created_at->format('d F Y, H:i:s') }} WIB via Portal Online Bank Majalengka
      </p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
        &larr; Kembali ke Daftar
      </a>
      @php
        $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $loan->hp));
        $waMessage = "Halo Bapak/Ibu {$loan->nama},\n\nTerima kasih telah mengajukan pinjaman di Perumda BPR Majalengka dengan nomor tiket {$loan->reference_code} sebesar Rp " . number_format($loan->nominal, 0, ',', '.') . " (Tenor {$loan->tenor} Bulan).\n\nKami dari Kantor Pelayanan BPR Majalengka ingin mengonfirmasi jadwal verifikasi berkas dan survei lapangan. Apakah Bapak/Ibu bersedia?";
      @endphp
      <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMessage) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl flex items-center gap-2 shadow transition">
        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
        <span>Hubungi WhatsApp Debitur</span>
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Left 2 Cols: Data Debitur, Finansial & Agunan -->
    <div class="md:col-span-2 space-y-6">

      <!-- Identitas Debitur -->
      <div class="bg-white rounded-2xl p-6 card-shadow">
        <h4 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
          <svg class="w-4 h-4 text-bpr-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          Identitas Lengkap Calon Debitur
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div>
            <span class="text-slate-400 block mb-0.5">Nama Lengkap Sesuai KTP</span>
            <span class="font-bold text-slate-900 text-sm">{{ $loan->nama }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Nomor Induk Kependudukan (NIK)</span>
            <span class="font-mono font-bold text-slate-800 text-sm">{{ $loan->nik }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Nomor HP / WhatsApp Aktif</span>
            <span class="font-mono font-semibold text-emerald-700">{{ $loan->hp }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Kode Afiliasi / Referal</span>
            <span class="font-semibold text-slate-700">{{ $loan->afiliasi ?: '-' }}</span>
          </div>
          <div class="sm:col-span-2">
            <span class="text-slate-400 block mb-0.5">Alamat Lengkap Sesuai KTP</span>
            <span class="text-slate-700 leading-relaxed">{{ $loan->alamat }}</span>
          </div>
        </div>
      </div>

      <!-- Detail Pinjaman & Keuangan -->
      <div class="bg-white rounded-2xl p-6 card-shadow">
        <h4 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
          <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Detail Pengajuan Pinjaman & Kemampuan Finansial
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100">
            <span class="text-slate-500 block mb-1">Besar Plafon Pinjaman</span>
            <span class="text-xl font-black text-bpr-navy">Rp {{ number_format($loan->nominal, 0, ',', '.') }}</span>
          </div>
          <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100">
            <span class="text-slate-500 block mb-1">Jangka Waktu (Tenor)</span>
            <span class="text-xl font-black text-amber-800">{{ $loan->tenor }} Bulan</span>
          </div>
          <div class="sm:col-span-2">
            <span class="text-slate-400 block mb-0.5">Tujuan Penggunaan Pinjaman</span>
            <span class="font-medium text-slate-800">{{ $loan->tujuan }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Sumber Pendapatan Utama</span>
            <span class="font-semibold text-slate-800">{{ $loan->sumber_pendapatan }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Pendapatan Tetap per Bulan</span>
            <span class="font-bold text-slate-800">Rp {{ number_format($loan->pendapatan_tetap, 0, ',', '.') }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Pendapatan Sampingan / Lainnya</span>
            <span class="text-slate-700">{{ $loan->pendapatan_lain ?: '-' }}</span>
          </div>
          <div>
            <span class="text-slate-400 block mb-0.5">Deskripsi Sumber Lain</span>
            <span class="text-slate-700">{{ $loan->deskripsi_lain ?: '-' }}</span>
          </div>
        </div>
      </div>

      <!-- Keterangan Agunan -->
      <div class="bg-white rounded-2xl p-6 card-shadow">
        <h4 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          Keterangan Agunan / Jaminan Diajukan
        </h4>
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 leading-relaxed font-medium">
          {{ $loan->agunan }}
        </div>
      </div>

    </div>

    <!-- Right 1 Col: Status Action & Catatan Petugas -->
    <div class="space-y-6">

      <!-- Form Update Status -->
      <div class="bg-white rounded-2xl p-6 card-shadow">
        <h4 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-100">
          Proses & Status Pengajuan
        </h4>

        <form method="POST" action="{{ route('admin.loans.updateStatus', $loan) }}" class="space-y-4">
          @csrf
          @method('PUT')

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ubah Status</label>
            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
              <option value="pending" {{ $loan->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
              <option value="contacted" {{ $loan->status === 'contacted' ? 'selected' : '' }}>Contacted (Sudah Dihubungi)</option>
              <option value="surveyed" {{ $loan->status === 'surveyed' ? 'selected' : '' }}>Surveyed (Dalam Proses Survei)</option>
              <option value="approved" {{ $loan->status === 'approved' ? 'selected' : '' }}>Approved (Pengajuan Disetujui)</option>
              <option value="rejected" {{ $loan->status === 'rejected' ? 'selected' : '' }}>Rejected (Pengajuan Ditolak)</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Petugas Analis</label>
            <textarea name="notes" rows="4" placeholder="Tuliskan catatan verifikasi, hasil survei, atau alasan penolakan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('notes', $loan->notes) }}</textarea>
          </div>

          <button type="submit" class="w-full py-2.5 px-4 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow transition">
            Perbarui Status Pengajuan
          </button>
        </form>
      </div>

      <!-- Action Box -->
      <div class="bg-white rounded-2xl p-6 card-shadow">
        <h4 class="font-bold text-slate-800 text-sm mb-3">Tindakan Lain</h4>
        <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}" onsubmit="return confirm('Peringatan: Berkas pengajuan {{ $loan->reference_code }} akan dihapus permanen. Lanjutkan?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="w-full py-2.5 px-4 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-xl border border-red-200 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Hapus Berkas Ini</span>
          </button>
        </form>
      </div>

    </div>

  </div>

</div>
@endsection
