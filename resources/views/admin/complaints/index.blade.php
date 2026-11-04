@extends('admin.layouts.app')

@section('title', 'Manajemen Pengaduan Nasabah')
@section('page-title', 'Kanal Pengaduan Nasabah & Pengawasan Anti-Fraud')

@section('content')
<div class="space-y-6">

  <!-- Single Admin Security Notice -->
  <div class="p-4 rounded-2xl bg-gradient-to-r from-bpr-navy to-bpr-dark text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 card-shadow">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-400 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
      </div>
      <div>
        <h4 class="font-bold text-sm text-white">Panel Khusus Pengelola Tunggal (Single Administrator)</h4>
        <p class="text-xs text-blue-200 mt-0.5">
          Modul ini dikhususkan hanya untuk Administrator Utama berwenang guna menjaga kerahasiaan identitas nasabah dan integritas saluran pengaduan.
        </p>
      </div>
    </div>
    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 whitespace-nowrap">
      Akses Terverifikasi
    </span>
  </div>

  <!-- Status Tabs -->
  <div class="flex items-center gap-2 p-1.5 bg-white rounded-2xl card-shadow overflow-x-auto">
    <a href="{{ route('admin.complaints.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ !request('status') && !request('risk') ? 'bg-bpr-navy text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Semua Pengaduan</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ !request('status') && !request('risk') ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $stats['total'] }}</span>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'diproses']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'diproses' ? 'bg-amber-500 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Diproses</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'diproses' ? 'bg-slate-900 text-amber-300' : 'bg-amber-100 text-amber-800' }}">{{ $stats['diproses'] }}</span>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'ditindaklanjuti']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'ditindaklanjuti' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Ditindaklanjuti</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'ditindaklanjuti' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $stats['ditindaklanjuti'] }}</span>
    </a>
    <a href="{{ route('admin.complaints.index', ['status' => 'selesai']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'selesai' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Selesai</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'selesai' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $stats['selesai'] }}</span>
    </a>
    <a href="{{ route('admin.complaints.index', ['risk' => 'high']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('risk') === 'high' ? 'bg-red-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Indikasi Fraud (High Risk)</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('risk') === 'high' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-800' }}">{{ $stats['high_risk'] }}</span>
    </a>
  </div>

  <!-- Search & Category Filter -->
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
    <p class="text-xs text-slate-500">
      Setiap pengaduan dilengkapi audit trail IP, user-agent, dan verifikasi anti-fraud otomatis.
    </p>

    <form method="GET" action="{{ route('admin.complaints.index') }}" class="flex items-center gap-2">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      @if(request('risk'))
        <input type="hidden" name="risk" value="{{ request('risk') }}">
      @endif

      <select name="category" onchange="this.form.submit()" class="py-2 px-3 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue shadow-sm">
        <option value="all">Semua Kategori</option>
        <option value="pelayanan" {{ request('category') === 'pelayanan' ? 'selected' : '' }}>Pelayanan Petugas</option>
        <option value="transaksi" {{ request('category') === 'transaksi' ? 'selected' : '' }}>Transaksi Perbankan</option>
        <option value="kredit" {{ request('category') === 'kredit' ? 'selected' : '' }}>Kredit & Pinjaman</option>
        <option value="tabungan_deposito" {{ request('category') === 'tabungan_deposito' ? 'selected' : '' }}>Tabungan & Deposito</option>
        <option value="kartu_atm" {{ request('category') === 'kartu_atm' ? 'selected' : '' }}>Kartu ATM / Fasilitas</option>
        <option value="indikasi_fraud" {{ request('category') === 'indikasi_fraud' ? 'selected' : '' }}>Indikasi Penipuan / Fraud</option>
        <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
      </select>

      <div class="relative w-full sm:w-64">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Kode Tiket, Nama, Email..." class="w-full py-2 pl-9 pr-3 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue shadow-sm">
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
      <button type="submit" class="px-3 py-2 bg-bpr-navy hover:bg-bpr-dark text-white rounded-xl text-xs font-bold transition">Cari</button>
    </form>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl card-shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
            <th class="py-3 px-4 w-12 text-center">No</th>
            <th class="py-3 px-4">Kode Tiket & Tanggal</th>
            <th class="py-3 px-4">Data Pelapor</th>
            <th class="py-3 px-4">Kategori & Perihal</th>
            <th class="py-3 px-4 text-center">Anti-Fraud Risk</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($complaints as $index => $c)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ $complaints->firstItem() + $index }}
              </td>
              <td class="py-3.5 px-4 font-mono">
                <span class="font-extrabold text-bpr-navy">{{ $c->ticket_code }}</span>
                <span class="block text-[10px] text-slate-400">{{ $c->created_at->format('d/m/Y H:i') }} WIB</span>
              </td>
              <td class="py-3.5 px-4">
                <p class="font-bold text-slate-900">{{ $c->nama }}</p>
                <p class="text-[11px] text-slate-500">{{ $c->email }}</p>
                <p class="text-[10px] text-slate-400 font-mono">{{ $c->telepon }}</p>
                @if($c->nomor_rekening)
                  <span class="inline-block px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-600 font-mono mt-0.5">Rek: {{ $c->nomor_rekening }}</span>
                @endif
              </td>
              <td class="py-3.5 px-4 max-w-xs">
                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700 mb-1">
                  {{ $c->kategori }}
                </span>
                <p class="font-bold text-slate-800 truncate" title="{{ $c->judul }}">{{ $c->judul }}</p>
                <p class="text-[11px] text-slate-500 truncate" title="{{ $c->isi_pengaduan }}">{{ Str::limit($c->isi_pengaduan, 60) }}</p>
              </td>
              <td class="py-3.5 px-4 text-center">
                @if($c->fraud_risk_level === 'high')
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800 border border-red-300">
                    <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Tinggi (Alert)
                  </span>
                @elseif($c->fraud_risk_level === 'medium')
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                    Sedang
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    Rendah (Aman)
                  </span>
                @endif
              </td>
              <td class="py-3.5 px-4 text-center">
                @if($c->status === 'selesai')
                  <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                    Selesai
                  </span>
                @elseif($c->status === 'ditindaklanjuti')
                  <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-800 border border-blue-300">
                    Ditindaklanjuti
                  </span>
                @else
                  <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                    Diproses
                  </span>
                @endif
              </td>
              <td class="py-3.5 px-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <a href="{{ route('admin.complaints.show', $c) }}" class="px-3 py-1.5 rounded-lg bg-bpr-navy hover:bg-bpr-dark text-white font-bold text-[11px] transition inline-flex items-center gap-1 shadow-sm">
                    <span>Kelola</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                  </a>
                  <form method="POST" action="{{ route('admin.complaints.destroy', $c) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengaduan ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Hapus Pengaduan" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg transition hover:bg-red-50">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <p class="font-medium">Tidak ada pengaduan nasabah yang ditemukan pada kriteria ini.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($complaints->hasPages())
      <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $complaints->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
