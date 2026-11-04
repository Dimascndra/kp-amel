@extends('admin.layouts.app')

@section('title', 'Keamanan Akun & Ganti Kata Sandi')
@section('page-title', 'Keamanan Akun & Pengaturan Kata Sandi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Header Banner -->
  <div class="bg-gradient-to-r from-bpr-navy via-bpr-blue to-blue-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
    <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-sm flex items-center justify-center text-2xl font-black text-amber-300 shadow-inner">
          {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
        <div>
          <h2 class="text-lg font-extrabold tracking-tight">{{ auth()->user()->name ?? 'Administrator BPR' }}</h2>
          <p class="text-xs text-blue-200 flex items-center gap-2 mt-0.5">
            <span>{{ auth()->user()->email }}</span>
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-300">Akses Tunggal Superadmin</span>
          </p>
        </div>
      </div>
      <div class="px-3.5 py-1.5 rounded-xl bg-white/10 border border-white/15 text-xs text-blue-100 flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <span>Portal Terproteksi Enkripsi Bcrypt</span>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Card 1: Form Ganti Kata Sandi -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 card-shadow border border-slate-100 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
          <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-800">Ubah Kata Sandi</h3>
            <p class="text-[11px] text-slate-400">Perbarui kata sandi untuk mengamankan akses panel admin.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.password') }}" id="form-change-password" class="space-y-4">
          @csrf
          @method('PUT')

          <div>
            <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi Saat Ini</label>
            <div class="relative">
              <input type="password" id="current_password" name="current_password" required
                placeholder="Masukkan kata sandi lama"
                class="w-full px-3.5 py-2.5 pr-10 rounded-xl border @error('current_password') border-red-300 bg-red-50/50 @else border-slate-200 @enderror text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
              <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
              </button>
            </div>
            @error('current_password')
              <p class="text-[11px] text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
            <div class="relative">
              <input type="password" id="password" name="password" required minlength="8"
                placeholder="Minimal 8 karakter"
                class="w-full px-3.5 py-2.5 pr-10 rounded-xl border @error('password') border-red-300 bg-red-50/50 @else border-slate-200 @enderror text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
              <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
              </button>
            </div>
            @error('password')
              <p class="text-[11px] text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
              <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                placeholder="Ulangi kata sandi baru"
                class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
              <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
              </button>
            </div>
          </div>

          <div class="pt-3">
            <button type="submit" class="w-full py-2.5 px-4 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span>Simpan Kata Sandi Baru</span>
            </button>
          </div>
        </form>
      </div>

      <div class="mt-6 p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/60 text-[11px] text-amber-900 leading-relaxed">
        <strong class="font-bold">Tips Keamanan:</strong> Gunakan kombinasi minimal 8 karakter dengan huruf kapital, huruf kecil, dan angka untuk menjaga integritas keamanan perbankan.
      </div>
    </div>

    <!-- Card 2: Form Informasi Akun & Email Login -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 card-shadow border border-slate-100 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
          <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-800">Profil & Email Administrator</h3>
            <p class="text-[11px] text-slate-400">Atur nama dan alamat email yang digunakan saat login.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
          @csrf
          @method('PUT')

          <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Administrator</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
              placeholder="Administrator BPR Majalengka"
              class="w-full px-3.5 py-2.5 rounded-xl border @error('name') border-red-300 bg-red-50/50 @else border-slate-200 @enderror text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue">
            @error('name')
              <p class="text-[11px] text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Login Administrator</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
              placeholder="admin@bankmajalengka.co.id"
              class="w-full px-3.5 py-2.5 rounded-xl border @error('email') border-red-300 bg-red-50/50 @else border-slate-200 @enderror text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue font-mono">
            <p class="text-[11px] text-slate-400 mt-1">Email ini digunakan sebagai akun masuk utama pada halaman login.</p>
            @error('email')
              <p class="text-[11px] text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Kontak / WhatsApp (Opsional)</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
              placeholder="Contoh: 08123456789"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-bpr-blue font-mono">
          </div>

          <div class="pt-3">
            <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span>Perbarui Profil & Email</span>
            </button>
          </div>
        </form>
      </div>

      <div class="mt-6 p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/60 text-[11px] text-blue-900 leading-relaxed">
        <strong class="font-bold">Info:</strong> Jika Anda memperbarui email administrator di atas, pastikan gunakan email baru tersebut saat login di <code>/admin/login</code>.
      </div>
    </div>

  </div>

</div>

<script>
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  const openIcon = btn.querySelector('.eye-open');
  const closedIcon = btn.querySelector('.eye-closed');
  if (input.type === 'password') {
    input.type = 'text';
    openIcon.classList.add('hidden');
    closedIcon.classList.remove('hidden');
  } else {
    input.type = 'password';
    openIcon.classList.remove('hidden');
    closedIcon.classList.add('hidden');
  }
}
</script>
@endsection
