@extends('layouts.admin')

@section('page_title', 'Buat Template Baru')

@section('content')
<div class="max-w-2xl">
    <!-- Breadcrumb / Back button -->
    <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Template
    </a>

    <!-- Create Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800 title-font">Metadata Template Baru</h3>
            <p class="text-xs text-slate-500 mt-0.5">Isi metadata awal dokumen formulir pengajuan change request</p>
        </div>

        <form action="{{ route('admin.templates.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Title -->
            <div class="space-y-2">
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Judul Form</label>
                <input type="text" id="title" name="title" value="{{ old('title', 'Form Pengajuan Change Request') }}" required
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm"
                       placeholder="Contoh: Form Pengajuan Change Request Infrastructure">
                @error('title')
                    <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Double Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Author -->
                <div class="space-y-2">
                    <label for="author" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Author (Penyusun)</label>
                    <input type="text" id="author" name="author" value="{{ old('author', 'Sistem Teknologi Informasi') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm"
                           placeholder="Contoh: Sistem Teknologi Informasi">
                    @error('author')
                        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Created Date -->
                <div class="space-y-2">
                    <label for="created_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Pembuatan</label>
                    <input type="date" id="created_date" name="created_date" value="{{ old('created_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm">
                    @error('created_date')
                        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Double Grid 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div class="space-y-2">
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status Dokumen</label>
                    <input type="text" id="status" name="status" value="{{ old('status', 'Aktif') }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm"
                           placeholder="Contoh: Aktif">
                    @error('status')
                        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Revision -->
                <div class="space-y-2">
                    <label for="revision" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Revisi Dokumen</label>
                    <input type="text" id="revision" name="revision" value="{{ old('revision', '01') }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm"
                           placeholder="Contoh: 01">
                    @error('revision')
                        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi Pengantar Form</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm"
                          placeholder="Untuk membantu kami memenuhi kebutuhan perubahan, mohon untuk memberikan informasi secara detail...">{{ old('description', 'Untuk membantu kami memenuhi kebutuhan perubahan, mohon untuk memberikan informasi secara detail mengenai perubahan yang akan dilakukan dengan mengisi formulir berikut :') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.templates.index') }}" class="px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold rounded-xl text-xs transition-colors">
                    Batalkan
                </a>
                <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-blue-600/10 transition-all">
                    Simpan & Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
