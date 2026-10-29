<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') | Admin Portal Perumda BPR Majalengka</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'bpr-navy': '#002D62',
            'bpr-dark': '#001A3A',
            'bpr-blue': '#0066CC',
            'bpr-accent': '#004C99',
            'bpr-amber': '#F59E0B',
            'bpr-red': '#DC2626',
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            mono: ['"JetBrains Mono"', 'monospace'],
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .card-shadow { box-shadow: 0 4px 20px -2px rgba(0, 45, 98, 0.06), 0 2px 6px -1px rgba(0, 0, 0, 0.04); }
    .sidebar-active { background-color: rgba(255, 255, 255, 0.12); border-left: 4px solid #F59E0B; }
  </style>
  @stack('styles')
</head>
<body class="bg-slate-100/90 text-slate-800 antialiased h-screen overflow-hidden flex flex-col">

  <div class="flex-1 flex min-h-0 overflow-hidden">
    <!-- Sidebar Desktop -->
    <aside class="hidden md:flex flex-col w-64 h-full bg-bpr-dark text-white flex-shrink-0 sticky top-0 overflow-hidden select-none">
      <!-- Brand -->
      <div class="p-5 border-b border-white/10 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-bpr-navy flex items-center justify-center font-black text-amber-400 text-lg shadow-md">
          BM
        </div>
        <div>
          <h1 class="font-extrabold text-sm leading-tight tracking-wide">BPR MAJALENGKA</h1>
          <p class="text-[10px] text-blue-300 font-medium">ADMIN CMS PORTAL</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.dashboard') ? 'sidebar-active text-white' : '' }}">
          <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Ringkasan Dashboard
        </a>

        <div class="pt-3 pb-1.5 px-3.5 text-[10px] font-bold text-blue-300/60 uppercase tracking-wider">Layanan Nasabah</div>

        <a href="{{ route('admin.loans.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.loans.*') ? 'sidebar-active text-white' : '' }}">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Pengajuan Pinjaman
          </div>
          @php $pendingCount = \App\Models\LoanApplication::where('status', 'pending')->count(); @endphp
          @if($pendingCount > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-900">{{ $pendingCount }}</span>
          @endif
        </a>

        <div class="pt-3 pb-1.5 px-3.5 text-[10px] font-bold text-blue-300/60 uppercase tracking-wider">Master Data</div>

        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.products.*') ? 'sidebar-active text-white' : '' }}">
          <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          Produk (24 Item)
        </a>

        <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.branches.*') ? 'sidebar-active text-white' : '' }}">
          <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          12 Kantor Kas
        </a>

        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.articles.*') ? 'sidebar-active text-white' : '' }}">
          <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
          Artikel & Edukasi
        </a>

        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.reports.*') ? 'sidebar-active text-white' : '' }}">
          <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Laporan Publikasi & GCG
        </a>

        <div class="pt-3 pb-1.5 px-3.5 text-[10px] font-bold text-blue-300/60 uppercase tracking-wider">Konfigurasi</div>

        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.settings.*') ? 'sidebar-active text-white' : '' }}">
          <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Pengaturan BPR & Kontak
        </a>

        <div class="pt-4 border-t border-white/10">
          <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-medium text-blue-200 hover:text-white hover:bg-white/10 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Buka Website Publik
          </a>
        </div>
      </nav>

      <!-- User footer -->
      <div class="p-4 border-t border-white/10 flex items-center justify-between">
        <div class="flex items-center gap-2.5 overflow-hidden">
          <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
          </div>
          <div class="overflow-hidden">
            <p class="text-xs font-bold truncate">{{ auth()->user()->name ?? 'Admin BPR' }}</p>
            <p class="text-[10px] text-blue-300 truncate">{{ auth()->user()->email ?? 'admin@bankmajalengka.co.id' }}</p>
          </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" title="Keluar" class="p-1.5 text-blue-300 hover:text-red-400 hover:bg-white/10 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto">
      <!-- Topbar Header -->
      <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-6 py-3 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
          <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <h2 class="text-base sm:text-lg font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
        </div>

        <div class="flex items-center gap-3">
          <div class="hidden sm:flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-bpr-navy border border-blue-200/60">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Server MySQL Native Aktif</span>
          </div>

          <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 text-xs font-bold text-white bg-bpr-navy hover:bg-bpr-blue rounded-xl flex items-center gap-1.5 transition-colors shadow-sm">
            <span>Lihat Website</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        </div>
      </header>

      <!-- Alert messages -->
      <div class="px-4 sm:px-6 pt-4">
        @if(session('success'))
          <div class="p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
          </div>
        @endif

        @if(session('info'))
          <div class="p-4 mb-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs sm:text-sm font-medium flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span>{{ session('info') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-900">&times;</button>
          </div>
        @endif

        @if($errors->any())
          <div class="p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm font-medium">
            <div class="flex items-center gap-2 mb-1.5 font-bold text-red-900">
              <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span>Mohon periksa kembali form berikut:</span>
            </div>
            <ul class="list-disc pl-7 space-y-1">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      <!-- Main Body -->
      <main class="flex-1 px-4 sm:px-6 py-6">
        @yield('content')
      </main>

      <!-- Footer -->
      <footer class="bg-white border-t border-slate-200 px-6 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>&copy; {{ date('Y') }} Perumda BPR Majalengka. Sistem Dashboard Internal Terintegrasi.</p>
        <p class="font-medium text-slate-400">Laravel 11 • Native MySQL • Zero Docker</p>
      </footer>
    </div>
  </div>

  <!-- Mobile Drawer Backdrop & Menu -->
  <div id="mobile-sidebar" class="fixed inset-0 z-50 flex hidden">
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-bpr-dark text-white p-5">
      <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-lg bg-blue-600 font-bold text-amber-400 flex items-center justify-center text-sm">BM</div>
          <span class="font-bold text-sm">BPR MAJALENGKA</span>
        </div>
        <button id="mobile-sidebar-close" class="text-white/70 hover:text-white p-1">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <nav class="space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">Dashboard</a>
        <a href="{{ route('admin.loans.index') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">Pengajuan Pinjaman</a>
        <a href="{{ route('admin.products.index') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">Produk</a>
        <a href="{{ route('admin.branches.index') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">12 Kantor Kas</a>
        <a href="{{ route('admin.articles.index') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">Artikel & Edukasi</a>
        <a href="{{ route('admin.reports.index') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">Laporan Publikasi</a>
        <a href="{{ route('admin.settings.index') }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white/90 hover:bg-white/10">Pengaturan BPR</a>
      </nav>
    </div>
  </div>

  <script>
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileClose = document.getElementById('mobile-sidebar-close');
    const mobileBackdrop = document.getElementById('mobile-sidebar-backdrop');

    if (mobileBtn && mobileSidebar) {
      mobileBtn.addEventListener('click', () => mobileSidebar.classList.remove('hidden'));
      mobileClose.addEventListener('click', () => mobileSidebar.classList.add('hidden'));
      mobileBackdrop.addEventListener('click', () => mobileSidebar.classList.add('hidden'));
    }
  </script>

  @stack('scripts')
</body>
</html>
