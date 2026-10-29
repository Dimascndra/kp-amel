@extends('admin.layouts.app')

@section('title', 'Manajemen Laporan Publikasi')
@section('page-title', 'Laporan Publikasi & Tata Kelola (GCG)')

@section('content')
<div class="space-y-6">

  <!-- Header Action & Search -->
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
    <div class="flex items-center gap-2">
      <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Dokumen:</span>
      <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-bpr-navy font-bold text-xs">{{ $reports->total() }} Laporan</span>
    </div>

    <div class="flex items-center gap-3">
      <form method="GET" action="{{ route('admin.reports.index') }}" class="relative flex-1 sm:w-64">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kategori..." class="w-full py-2 pl-9 pr-3 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </form>

      <a href="{{ route('admin.reports.create') }}" class="px-4 py-2 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl flex items-center gap-1.5 shadow-sm transition whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Laporan</span>
      </a>
    </div>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl card-shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
            <th class="py-3 px-4 w-12 text-center">No</th>
            <th class="py-3 px-4">Kategori Laporan</th>
            <th class="py-3 px-4">Judul Dokumen Publikasi</th>
            <th class="py-3 px-4">Periode</th>
            <th class="py-3 px-4">Ukuran File</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($reports as $index => $rep)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ $reports->firstItem() + $index }}
              </td>
              <td class="py-3.5 px-4">
                @php
                  $colorStyles = [
                    'blue' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'amber' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'slate' => 'bg-slate-100 text-slate-700 border-slate-200',
                  ];
                  $badgeClass = $colorStyles[$rep->category_color] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                @endphp
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                  {{ $rep->category }}
                </span>
              </td>
              <td class="py-3.5 px-4">
                <p class="font-bold text-slate-900">{{ $rep->title }}</p>
                <p class="text-[11px] text-slate-500 truncate max-w-xs">{{ $rep->description }}</p>
              </td>
              <td class="py-3.5 px-4 text-slate-600 font-medium">
                {{ $rep->period ?: '-' }}
              </td>
              <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                {{ $rep->file_size }}
              </td>
              <td class="py-3.5 px-4 text-center">
                <form method="POST" action="{{ route('admin.reports.toggle', $rep) }}" class="inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" title="Klik untuk ubah status" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition {{ $rep->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                    {{ $rep->is_active ? 'TAMPIL' : 'SEMBUNYI' }}
                  </button>
                </form>
              </td>
              <td class="py-3.5 px-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <a href="{{ route('admin.reports.edit', $rep) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Laporan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <form method="POST" action="{{ route('admin.reports.destroy', $rep) }}" onsubmit="return confirm('Hapus laporan {{ $rep->title }}?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                Tidak ada laporan publikasi ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($reports->hasPages())
      <div class="p-4 border-t border-slate-100">
        {{ $reports->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
