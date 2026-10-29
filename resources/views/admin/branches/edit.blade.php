@extends('admin.layouts.app')

@section('title', 'Edit Kantor Kas: ' . $branch->name)
@section('page-title', 'Edit Kantor Kas')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow">
    <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
      <div>
        <h3 class="text-base font-bold text-slate-800">Edit Data: {{ $branch->name }}</h3>
        <p class="text-xs text-slate-400">Perbarui data operasional dan alamat kantor kas.</p>
      </div>
      <a href="{{ route('admin.branches.index') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
        &larr; Kembali
      </a>
    </div>

    <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kantor / Kas <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name', $branch->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Kantor <span class="text-red-500">*</span></label>
          <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
            <option value="Kas" {{ old('type', $branch->type) === 'Kas' ? 'selected' : '' }}>Kantor Kas</option>
            <option value="Pusat" {{ old('type', $branch->type) === 'Pusat' ? 'selected' : '' }}>Kantor Pusat Operasional</option>
            <option value="Cabang" {{ old('type', $branch->type) === 'Cabang' ? 'selected' : '' }}>Kantor Cabang</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
          <textarea name="address" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">{{ old('address', $branch->address) }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor</label>
          <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Layanan</label>
          <input type="text" name="whatsapp" value="{{ old('whatsapp', $branch->whatsapp) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Operasional Layanan <span class="text-red-500">*</span></label>
          <input type="text" name="hours" value="{{ old('hours', $branch->hours) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil (Sort Order)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $branch->sort_order) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-bpr-blue">
        </div>

        <div class="flex items-center gap-6 pt-2">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_main" value="1" {{ old('is_main', $branch->is_main) ? 'checked' : '' }} class="w-4 h-4 rounded text-bpr-blue">
            <span class="text-xs font-bold text-slate-700">Kantor Utama (Pusat)</span>
          </label>

          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-bpr-blue">
            <span class="text-xs font-bold text-slate-700">Buka Layanan (Aktif)</span>
          </label>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin.branches.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-bpr-navy hover:bg-bpr-blue text-white font-bold text-xs rounded-xl shadow transition">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
