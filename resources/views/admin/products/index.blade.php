@extends('admin.layouts.app')

@section('title', 'Manajemen Produk')
@section('page-title', 'Katalog Produk Perbankan (24 Item)')

@section('content')
<div class="space-y-6">

  <!-- Header Action & Filters -->
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
    <!-- Category Tabs -->
    <div class="flex items-center gap-1.5 p-1 bg-white rounded-xl card-shadow overflow-x-auto">
      <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('category') ? 'bg-bpr-navy text-white' : 'text-slate-600 hover:bg-slate-100' }}">
        Semua ({{ \App\Models\Product::count() }})
      </a>
      <a href="{{ route('admin.products.index', ['category' => 'tabungan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('category') === 'tabungan' ? 'bg-bpr-navy text-white' : 'text-slate-600 hover:bg-slate-100' }}">
        Tabungan ({{ \App\Models\Product::where('category', 'tabungan')->count() }})
      </a>
      <a href="{{ route('admin.products.index', ['category' => 'deposito']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('category') === 'deposito' ? 'bg-bpr-navy text-white' : 'text-slate-600 hover:bg-slate-100' }}">
        Deposito ({{ \App\Models\Product::where('category', 'deposito')->count() }})
      </a>
      <a href="{{ route('admin.products.index', ['category' => 'kredit']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('category') === 'kredit' ? 'bg-bpr-navy text-white' : 'text-slate-600 hover:bg-slate-100' }}">
        Kredit ({{ \App\Models\Product::where('category', 'kredit')->count() }})
      </a>
    </div>

    <!-- Search & Create Button -->
    <div class="flex items-center gap-3">
      <form method="GET" action="{{ route('admin.products.index') }}" class="relative flex-1 sm:w-64">
        @if(request('category'))
          <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode produk..." class="w-full py-2 pl-9 pr-3 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </form>

      <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl flex items-center gap-1.5 shadow-sm transition whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Produk</span>
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
            <th class="py-3 px-4">Kode & Kategori</th>
            <th class="py-3 px-4">Nama Produk</th>
            <th class="py-3 px-4">Badge / Highlight</th>
            <th class="py-3 px-4">Tipe Aksi</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($products as $index => $prod)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ $products->firstItem() + $index }}
              </td>
              <td class="py-3.5 px-4">
                <span class="font-mono font-bold text-slate-800">{{ $prod->code }}</span>
                <span class="block text-[10px] uppercase font-semibold text-bpr-blue">{{ $prod->category }}</span>
              </td>
              <td class="py-3.5 px-4">
                <p class="font-bold text-slate-900">{{ $prod->name }}</p>
                <p class="text-[11px] text-slate-500 truncate max-w-xs">{{ $prod->slogan }}</p>
              </td>
              <td class="py-3.5 px-4">
                @if($prod->badge)
                  <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                    {{ $prod->badge }}
                  </span>
                @else
                  <span class="text-slate-400 text-xs">-</span>
                @endif
              </td>
              <td class="py-3.5 px-4">
                <span class="text-[11px] font-semibold text-slate-600">
                  {{ $prod->cta_type === 'form_pengajuan' ? 'Form Online' : 'WhatsApp CS' }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-center">
                <form method="POST" action="{{ route('admin.products.toggle', $prod) }}" class="inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" title="Klik untuk ubah status" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition {{ $prod->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                    {{ $prod->is_active ? 'AKTIF' : 'NONAKTIF' }}
                  </button>
                </form>
              </td>
              <td class="py-3.5 px-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <a href="{{ route('admin.products.edit', $prod) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Produk">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" onsubmit="return confirm('Hapus produk {{ $prod->name }}?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Produk">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                Tidak ada produk ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($products->hasPages())
      <div class="p-4 border-t border-slate-100">
        {{ $products->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
