@extends('admin.layouts.app')

@section('title', 'Manajemen Pengajuan Pinjaman')
@section('page-title', 'Daftar Pengajuan Pinjaman Masuk')

@section('content')
<div class="space-y-6">

  <!-- Status Tabs -->
  <div class="flex items-center gap-2 p-1.5 bg-white rounded-2xl card-shadow overflow-x-auto">
    <a href="{{ route('admin.loans.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ !request('status') ? 'bg-bpr-navy text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Semua Berkas</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $statusCounts['all'] }}</span>
    </a>
    <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Menunggu (Pending)</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'pending' ? 'bg-slate-900 text-amber-300' : 'bg-amber-100 text-amber-800' }}">{{ $statusCounts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.loans.index', ['status' => 'contacted']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'contacted' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Dihubungi</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'contacted' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $statusCounts['contacted'] }}</span>
    </a>
    <a href="{{ route('admin.loans.index', ['status' => 'surveyed']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'surveyed' ? 'bg-purple-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Disurvei</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'surveyed' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-800' }}">{{ $statusCounts['surveyed'] }}</span>
    </a>
    <a href="{{ route('admin.loans.index', ['status' => 'approved']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'approved' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Disetujui (Approved)</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $statusCounts['approved'] }}</span>
    </a>
    <a href="{{ route('admin.loans.index', ['status' => 'rejected']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
      <span>Ditolak</span>
      <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('status') === 'rejected' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-800' }}">{{ $statusCounts['rejected'] }}</span>
    </a>
  </div>

  <!-- Search Filter -->
  <div class="flex items-center justify-between gap-4">
    <p class="text-xs text-slate-500">
      Menampilkan daftar calon debitur pemohon fasilitas pembiayaan secara real-time.
    </p>

    <form method="GET" action="{{ route('admin.loans.index') }}" class="relative w-full max-w-sm">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK, Nama, No HP, Kode Tiket..." class="w-full py-2 pl-9 pr-3 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue shadow-sm">
      <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </form>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl card-shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
            <th class="py-3 px-4 w-12 text-center">No</th>
            <th class="py-3 px-4">Nomor Tiket & Waktu</th>
            <th class="py-3 px-4">Calon Debitur</th>
            <th class="py-3 px-4">Kontak WhatsApp</th>
            <th class="py-3 px-4">Besar Pengajuan</th>
            <th class="py-3 px-4">Tenor</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($loans as $index => $loan)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ $loans->firstItem() + $index }}
              </td>
              <td class="py-3.5 px-4 font-mono">
                <span class="font-bold text-slate-800">{{ $loan->reference_code }}</span>
                <span class="block text-[10px] text-slate-400">{{ $loan->created_at->format('d M Y H:i') }}</span>
              </td>
              <td class="py-3.5 px-4">
                <p class="font-bold text-slate-900">{{ $loan->nama }}</p>
                <p class="text-[11px] text-slate-500 font-mono">NIK: {{ $loan->nik }}</p>
                <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ $loan->alamat }}</p>
              </td>
              <td class="py-3.5 px-4 font-mono text-[11px]">
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $loan->hp)) }}?text={{ urlencode('Halo Bapak/Ibu ' . $loan->nama . ', kami dari Tim Analis Perumda BPR Majalengka mengonfirmasi pengajuan pinjaman tiket ' . $loan->reference_code . '.') }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold transition">
                  <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                  <span>{{ $loan->hp }}</span>
                </a>
              </td>
              <td class="py-3.5 px-4 font-bold text-bpr-navy">
                Rp {{ number_format($loan->nominal, 0, ',', '.') }}
              </td>
              <td class="py-3.5 px-4 text-slate-600 font-medium">
                {{ $loan->tenor }} Bulan
              </td>
              <td class="py-3.5 px-4 text-center">
                @php
                  $statusStyles = [
                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'contacted' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'surveyed' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                  ];
                  $badgeStyle = $statusStyles[$loan->status] ?? 'bg-slate-100 text-slate-700';
                @endphp
                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeStyle }} uppercase">
                  {{ $loan->status }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <a href="{{ route('admin.loans.show', $loan) }}" class="px-3 py-1.5 rounded-lg bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-[11px] transition shadow-sm">
                    Tinjau Berkas
                  </a>
                  <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}" onsubmit="return confirm('Hapus berkas pengajuan {{ $loan->reference_code }}?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Berkas">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="py-8 text-center text-slate-400 text-xs">
                Tidak ada pengajuan pinjaman pada kategori ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($loans->hasPages())
      <div class="p-4 border-t border-slate-100">
        {{ $loans->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
