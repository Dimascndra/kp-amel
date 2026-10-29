@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')
@section('page-title', 'Ringkasan Dashboard BPR Majalengka')

@section('content')
<div class="space-y-6">

  <!-- Metric Cards Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <!-- Card 1: Pengajuan Pinjaman -->
    <div class="bg-white rounded-2xl p-5 card-shadow border-l-4 border-amber-500 flex items-center justify-between">
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengajuan Pinjaman</p>
        <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $metrics['total_loans'] }} <span class="text-xs font-semibold text-slate-500">Berkas</span></h3>
        <p class="text-[11px] text-amber-600 font-semibold mt-1">
          <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span>{{ $metrics['loans_pending'] }} Menunggu Verifikasi
        </p>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
    </div>

    <!-- Card 2: Total Produk -->
    <div class="bg-white rounded-2xl p-5 card-shadow border-l-4 border-bpr-blue flex items-center justify-between">
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Produk Aktif</p>
        <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $metrics['total_products'] }} <span class="text-xs font-semibold text-slate-500">Item</span></h3>
        <p class="text-[11px] text-slate-500 font-medium mt-1">
          {{ $metrics['total_tabungan'] }} Tabungan • {{ $metrics['total_deposito'] }} Deposito • {{ $metrics['total_kredit'] }} Kredit
        </p>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-blue-50 text-bpr-blue flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
      </div>
    </div>

    <!-- Card 3: Jaringan Kantor Kas -->
    <div class="bg-white rounded-2xl p-5 card-shadow border-l-4 border-emerald-500 flex items-center justify-between">
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jaringan Kantor</p>
        <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $metrics['total_branches'] }} <span class="text-xs font-semibold text-slate-500">Unit</span></h3>
        <p class="text-[11px] text-emerald-600 font-semibold mt-1">1 Pusat Operasional + 11 Kas</p>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
    </div>

    <!-- Card 4: Total Plafon Pinjaman -->
    <div class="bg-white rounded-2xl p-5 card-shadow border-l-4 border-purple-500 flex items-center justify-between">
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Nilai Pengajuan</p>
        <h3 class="text-xl font-black text-slate-800 mt-1">Rp {{ number_format($metrics['total_nominal_loans'], 0, ',', '.') }}</h3>
        <p class="text-[11px] text-purple-600 font-medium mt-1">
          {{ $metrics['loans_approved'] }} Disetujui • {{ $metrics['loans_contacted'] }} Diproses
        </p>
      </div>
      <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
    </div>
  </div>

  <!-- Quick Action Banner -->
  <div class="bg-gradient-to-r from-bpr-dark to-bpr-navy rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
    <div>
      <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 px-2.5 py-1 rounded bg-white/10">Akses Cepat Pengelolaan</span>
      <h3 class="text-lg font-bold mt-1.5">Kelola Konten & Data Perbankan Dinamis</h3>
      <p class="text-xs text-blue-200 mt-0.5">Semua perubahan pada produk, kantor kas, berita, dan laporan akan langsung tersinkronisasi ke website utama.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('admin.loans.index') }}" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs flex items-center gap-1.5 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        Tinjau Pinjaman ({{ $metrics['loans_pending'] }})
      </a>
      <a href="{{ route('admin.products.create') }}" class="px-3.5 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs flex items-center gap-1.5 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Produk
      </a>
      <a href="{{ route('admin.articles.create') }}" class="px-3.5 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs flex items-center gap-1.5 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Tulis Artikel
      </a>
    </div>
  </div>

  <!-- 2 Columns: Recent Loans & Recent Content -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Recent Loan Applications (2 Cols) -->
    <div class="lg:col-span-2 bg-white rounded-2xl card-shadow overflow-hidden">
      <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
          <h3 class="font-bold text-slate-800 text-sm">Pengajuan Pinjaman Masuk Terbaru</h3>
          <p class="text-xs text-slate-400">Daftar calon debitur yang mengajukan pinjaman via formulir website.</p>
        </div>
        <a href="{{ route('admin.loans.index') }}" class="text-xs text-bpr-blue font-bold hover:underline">Lihat Semua &rarr;</a>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
              <th class="py-3 px-4">Ref & Tanggal</th>
              <th class="py-3 px-4">Nama Debitur</th>
              <th class="py-3 px-4">Nominal</th>
              <th class="py-3 px-4">Tenor</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            @forelse($recentLoans as $loan)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-4 font-mono">
                  <span class="font-bold text-slate-800">{{ $loan->reference_code }}</span>
                  <span class="block text-[10px] text-slate-400">{{ $loan->created_at->format('d M Y H:i') }}</span>
                </td>
                <td class="py-3.5 px-4 font-semibold text-slate-900">
                  {{ $loan->nama }}
                  <span class="block text-[11px] font-normal text-slate-500">NIK: {{ substr($loan->nik, 0, 6) }}******{{ substr($loan->nik, -4) }}</span>
                </td>
                <td class="py-3.5 px-4 font-bold text-bpr-navy">
                  Rp {{ number_format($loan->nominal, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ $loan->tenor }} Bulan
                </td>
                <td class="py-3.5 px-4">
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
                  <a href="{{ route('admin.loans.show', $loan) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-bpr-blue font-bold text-[11px] transition">
                    Tinjau
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                  Belum ada pengajuan pinjaman baru.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Right Side: Content Overview & Articles (1 Col) -->
    <div class="space-y-6">
      <!-- Status Box -->
      <div class="bg-white rounded-2xl p-5 card-shadow">
        <h3 class="font-bold text-slate-800 text-sm mb-3">Distribusi Status Pinjaman</h3>
        <div class="space-y-2.5">
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Menunggu (Pending)
            </span>
            <span class="font-bold">{{ $metrics['loans_pending'] }}</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Sudah Dihubungi
            </span>
            <span class="font-bold">{{ $metrics['loans_contacted'] }}</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Disetujui (Approved)
            </span>
            <span class="font-bold">{{ $metrics['loans_approved'] }}</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Ditolak (Rejected)
            </span>
            <span class="font-bold">{{ $metrics['loans_rejected'] }}</span>
          </div>
        </div>
      </div>

      <!-- Articles list -->
      <div class="bg-white rounded-2xl p-5 card-shadow">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-bold text-slate-800 text-sm">Artikel & Literasi</h3>
          <a href="{{ route('admin.articles.index') }}" class="text-xs text-bpr-blue font-bold hover:underline">Kelola</a>
        </div>
        <div class="space-y-3">
          @foreach($recentArticles as $art)
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg bg-blue-100 text-bpr-blue flex items-center justify-center font-bold text-xs flex-shrink-0">
                EDU
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate">{{ $art->title }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $art->tag }} • {{ $art->published_at ? $art->published_at->format('d M Y') : '-' }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

</div>
@endsection
