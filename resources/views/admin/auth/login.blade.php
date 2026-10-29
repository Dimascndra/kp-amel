<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin Portal | Perumda BPR Majalengka</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'bpr-navy': '#002D62',
            'bpr-dark': '#001A3A',
            'bpr-blue': '#0066CC',
            'bpr-amber': '#F59E0B',
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-bpr-dark via-bpr-navy to-slate-900 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-md">
    <!-- Brand Header -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-xl mb-4 border border-white/20">
        <span class="text-2xl font-black text-bpr-navy tracking-tight">BM</span>
      </div>
      <h1 class="text-2xl font-extrabold text-white tracking-tight">PERUMDA BPR MAJALENGKA</h1>
      <p class="text-xs text-blue-200 mt-1">Sistem Informasi & Portal Manajemen Internal</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-3xl p-8 shadow-2xl border border-white/20">
      <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Masuk ke Dashboard</h2>
        <p class="text-xs text-slate-500 mt-1">Silakan masukkan email dan kata sandi administrator.</p>
      </div>

      @if(session('info'))
        <div class="mb-4 p-3 rounded-xl bg-blue-50 text-blue-700 text-xs font-medium border border-blue-200">
          {{ session('info') }}
        </div>
      @endif

      @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-xs font-medium border border-red-200">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
        @csrf

        <div>
          <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Administrator</label>
          <div class="relative">
            <input type="email" id="email" name="email" value="{{ old('email', 'admin@bankmajalengka.co.id') }}" required autofocus
              placeholder="admin@bankmajalengka.co.id"
              class="w-full px-4 py-3 rounded-xl border border-slate-300 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-bpr-blue focus:border-transparent transition">
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
            <span class="text-[11px] text-slate-400">Default: password</span>
          </div>
          <div class="relative">
            <input type="password" id="password" name="password" required
              placeholder="••••••••"
              class="w-full px-4 py-3 rounded-xl border border-slate-300 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-bpr-blue focus:border-transparent transition">
          </div>
        </div>

        <div class="flex items-center justify-between pt-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-bpr-blue focus:ring-bpr-blue">
            <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
          </label>
          <span class="text-xs text-bpr-blue font-semibold">Keamanan Internal Terenkripsi</span>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow-lg shadow-bpr-navy/30 transition-colors flex items-center justify-center gap-2 mt-2">
          <span>Masuk ke Portal Admin</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
      </form>

      <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
        <span>Berizin & Diawasi OJK</span>
        <span>Dijamin LPS 2 Miliar</span>
      </div>
    </div>

    <!-- Back to public link -->
    <div class="text-center mt-6">
      <a href="{{ route('home') }}" class="text-xs text-blue-200/80 hover:text-white flex items-center justify-center gap-1.5 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Kembali ke Beranda Website Utama</span>
      </a>
    </div>
  </div>

</body>
</html>
