@extends('layouts.admin')

@section('page_title', 'Master Template Builder')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#173D8F] transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Template
        </a>
    </div>

    <!-- Main Builder Layout: 2 Columns (Left 40% Builder, Right 60% Live Preview) -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        
        <!-- Left Side: Form Builder (40% width - Spans 2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Metadata Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <!-- Metadata Header with Generate Preview Button -->
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#173D8F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Metadata Form
                    </h3>
                    <button type="button" id="generate-preview-btn" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition-all shrink-0">
                        Generate Preview
                    </button>
                </div>
                
                <form id="metadata-form" action="{{ route('admin.templates.update', $template->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Judul Form</label>
                        <input type="text" name="title" value="{{ old('title', $template->title) }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Author</label>
                        <input type="text" name="author" value="{{ old('author', $template->author) }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Status</label>
                            <input type="text" name="status" value="{{ old('status', $template->status) }}" required
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Revisi</label>
                            <input type="text" name="revision" value="{{ old('revision', $template->revision) }}" required
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pengantar</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 py-1">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}
                               class="rounded border-slate-300 text-[#173D8F] focus:ring-[#173D8F]/20">
                        <label for="is_active" class="text-xs font-semibold text-slate-700 cursor-pointer select-none">Aktifkan Template Form ini</label>
                    </div>

                    <button type="submit" class="hidden"></button>
                </form>
                <script>
                document.getElementById('generate-preview-btn').addEventListener('click', function(){
                    const form = document.getElementById('metadata-form');
                    const url = form.action;
                    const data = new FormData(form);
                    fetch(url, {
                        method: form.method,
                        body: data,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.ok ? response.text() : Promise.reject('Network error'))
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newPreview = doc.querySelector('#live-preview');
                        if (newPreview) {
                            document.querySelector('#live-preview').innerHTML = newPreview.innerHTML;
                        } else {
                            location.reload();
                        }
                    })
                    .catch(err => console.error(err));
                });
                </script>
            </div>

            <!-- Add Section Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#173D8F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Bagian (Section)
                </h3>
                
                <form action="{{ route('admin.templates.sections.store', $template->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Judul Bagian</label>
                        <input type="text" name="title" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none"
                               placeholder="Contoh: 2. Detail Perubahan">
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition-colors border border-slate-200">
                        + Tambah Bagian
                    </button>
                </form>
            </div>

            <!-- Sections & Fields List -->
            <div class="space-y-4">
                <!-- Static Info Card (Section 1) -->
                <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-300 p-5 flex gap-3.5 items-start">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#173D8F] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-xs font-bold text-slate-800 block uppercase tracking-wider">1. Section Identitas (Statis)</span>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Bagian header identitas dan detail pemohon/peruntukan (Nama, Jabatan, Departemen, Tanggal Pengajuan, SLA) sudah terpasang secara otomatis dan statis pada form, tidak perlu dikonfigurasi secara manual.
                        </p>
                    </div>
                </div>

                <!-- Dynamic Sections -->
                @if($template->sections->isEmpty())
                    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm text-center">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        <h4 class="text-xs font-bold text-slate-700">Belum ada bagian formulir</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">Gunakan panel di atas untuk menambahkan bagian baru.</p>
                    </div>
                @else
                    @foreach($template->sections as $sec)
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                            <!-- Section Header -->
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
                                <form action="{{ route('admin.templates.sections.update', $sec->id) }}" method="POST" class="flex items-center gap-2 flex-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="title" value="{{ $sec->title }}" 
                                           class="text-xs font-bold text-slate-800 bg-transparent border border-transparent hover:border-slate-200 focus:border-slate-300 focus:bg-slate-50 rounded-lg px-2 py-1 outline-none flex-1 transition-all" 
                                           title="Klik untuk mengubah judul">
                                    <input type="number" name="order" value="{{ $sec->order }}" 
                                           class="w-10 text-center text-xs bg-slate-50 border border-slate-200 rounded-lg py-1 outline-none focus:border-[#173D8F]" 
                                           title="Order">
                                    <button type="submit" class="text-[10px] text-blue-600 hover:text-blue-700 font-bold uppercase transition-colors shrink-0">Simpan</button>
                                </form>

                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button" 
                                            onclick="openAddFieldModal({{ $sec->id }}, '{{ addslashes($sec->title) }}')"
                                            class="text-[10px] bg-[#173D8F] hover:bg-opacity-95 text-white font-semibold px-2 py-1 rounded-lg transition-all shrink-0">
                                        + Tambah Field
                                    </button>
                                    
                                    <form action="{{ route('admin.templates.sections.destroy', $sec->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bagian ini? Semua kolom di dalamnya akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 hover:bg-red-50 text-red-500 rounded-lg transition-colors" title="Hapus Bagian">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Fields List in Section -->
                            <div class="space-y-2">
                                @if($sec->fields->isEmpty())
                                    <p class="text-[11px] text-slate-400 italic text-center py-2">Belum ada kolom di bagian ini.</p>
                                @else
                                    @foreach($sec->fields as $fld)
                                        <div id="field-card-{{ $fld->id }}" class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-2 flex-wrap min-w-0">
                                                <span class="text-xs font-bold text-slate-700 truncate" title="{{ $fld->label }}">{{ $fld->label }}</span>
                                                <span class="text-[9px] font-bold px-1.5 py-0.5 bg-blue-50 text-[#173D8F] border border-blue-100 rounded-md uppercase tracking-wider shrink-0">
                                                    {{ $fld->type }}
                                                </span>
                                                @if($fld->is_required)
                                                    <span class="text-[9px] text-red-500 font-bold bg-red-50 border border-red-100 rounded-md px-1 py-0.5 shrink-0">* Wajib</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-2.5 shrink-0">
                                                <button type="button" 
                                                        onclick="openEditFieldModal({{ json_encode($fld) }}, '{{ addslashes($sec->title) }}')" 
                                                        class="text-xs text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                                                    Edit
                                                </button>
                                                
                                                <form action="{{ route('admin.templates.fields.destroy', $fld->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kolom ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

        <!-- Right Side: Live Preview (60% width - Spans 3 columns) -->
        <div class="lg:col-span-3">
            <div id="live-preview" class="sticky top-6 space-y-4">
                
                <!-- Preview Header & Control Panel -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between gap-4">
                    <div class="space-y-0.5 flex-1 min-w-0">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Live Preview</span>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs font-bold text-slate-700 block truncate max-w-[180px] sm:max-w-[280px]">
                                Template: <span class="font-extrabold text-slate-900">{{ $template->title }}</span>
                            </h3>
                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full {{ $template->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }} shrink-0">
                                {{ $template->is_active ? 'Aktif' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- A4 Simulated Canvas -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-10 space-y-6 overflow-y-auto max-h-[calc(100vh-14rem)] min-h-[600px] text-slate-800">
                    
                    <!-- Form Header Table (PT Dahana CR Style) -->
                    <div class="border border-slate-300 rounded-lg overflow-hidden text-xs bg-white">
                        <div class="grid grid-cols-1 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-300">
                            <!-- Logo -->
                            <div class="p-4 flex items-center justify-center bg-slate-50/50 shrink-0">
                                <img src="{{ asset('images/logo-dahana.png') }}" alt="PT Dahana" class="h-16 w-auto">
                            </div>
                            <!-- Title -->
                            <div class="sm:col-span-2 p-4 flex flex-col justify-center text-center">
                                <h2 class="text-xs font-black text-slate-800 uppercase tracking-tight leading-snug">
                                    {{ $template->title }}
                                </h2>
                            </div>
                            <!-- Metadata table -->
                            <div class="text-[9px] grid grid-cols-2 divide-x divide-y divide-slate-300 shrink-0">
                                <div class="p-1.5">
                                    <span class="text-slate-400 block font-semibold">AUTHOR</span>
                                    <span class="text-slate-700 font-bold uppercase truncate block">{{ $template->author ?? '-' }}</span>
                                </div>
                                <div class="p-1.5">
                                    <span class="text-slate-400 block font-semibold">TANGGAL</span>
                                    <span class="text-slate-700 font-bold block">{{ $template->created_date ? $template->created_date->format('d M Y') : '-' }}</span>
                                </div>
                                <div class="p-1.5">
                                    <span class="text-slate-400 block font-semibold">STATUS</span>
                                    <span class="text-slate-700 font-bold uppercase block">{{ $template->status ?? 'AKTIF' }}</span>
                                </div>
                                <div class="p-1.5">
                                    <span class="text-slate-400 block font-semibold">REVISI</span>
                                    <span class="text-slate-700 font-bold block">{{ $template->revision ?? '01' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description / Pengantar -->
                    @if($template->description)
                        <div class="text-[11px] text-slate-500 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                            {{ $template->description }}
                        </div>
                    @endif

                    <!-- Section 1: Identitas Pemohon & Peruntukan (Statis) -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-200 pb-1.5">
                            <span class="w-5 h-5 rounded bg-[#173D8F] text-white flex items-center justify-center text-[10px] font-bold">1</span>
                            Identitas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-slate-200 rounded-xl bg-slate-50/20 text-[11px]">
                            <!-- Pemohon Column -->
                            <div class="space-y-2.5">
                                <span class="text-[9px] font-bold text-blue-900 uppercase tracking-wider block bg-blue-50/50 px-2 py-0.5 rounded-md">Pemohon</span>
                                <div class="space-y-2">
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Nama Pemohon</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="Nama Staff Pemohon">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Jabatan</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="Supervisor / Staff">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Departemen</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="Sistem Teknologi Informasi">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Tanggal Pengajuan</label>
                                        <input type="date" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Peruntukan Column -->
                            <div class="space-y-2.5">
                                <span class="text-[9px] font-bold text-orange-900 uppercase tracking-wider block bg-orange-50/50 px-2 py-0.5 rounded-md">Peruntukan</span>
                                <div class="space-y-2">
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Nama Peruntukan</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="Nama Manfaat">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Jabatan</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="Manager / Senior Manager">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Departemen</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="Operasional / SDM">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">SLA Deadline</label>
                                        <input type="text" disabled class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-not-allowed" value="3 Hari Kerja">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Sections and Fields -->
                    @foreach($template->sections as $sec)
                        @if(str_contains(strtolower($sec->title), 'approval')) @continue @endif
                        <div class="space-y-3">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-200 pb-1.5">
                                {{ $sec->title }}
                            </h3>
                            
                            <div class="space-y-4">
                                @if($sec->fields->isEmpty())
                                    <p class="text-[11px] text-slate-400 italic py-1">Belum ada kolom dikonfigurasi.</p>
                                @else
                                    @foreach($sec->fields as $field)
                                        <div class="space-y-1">
                                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wide">
                                                {{ $field->label }}
                                                @if($field->is_required)
                                                    <span class="text-red-500 font-bold">*</span>
                                                @endif
                                            </label>

                                            <!-- TEXT Input -->
                                            @if($field->type === 'text')
                                                <input type="text" disabled class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs cursor-not-allowed" 
                                                       placeholder="{{ $field->config['placeholder'] ?? 'Tulis isian di sini...' }}">

                                            <!-- TEXTAREA Input -->
                                            @elseif($field->type === 'textarea')
                                                <textarea disabled rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs cursor-not-allowed" 
                                                          placeholder="{{ $field->config['placeholder'] ?? 'Tulis penjelasan lengkap di sini...' }}"></textarea>

                                            <!-- CHECKBOX Centang Tunggal -->
                                            @elseif($field->type === 'checkbox')
                                                <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-not-allowed select-none">
                                                    <input type="checkbox" disabled class="rounded border-slate-300 text-[#173D8F]">
                                                    Centang untuk memilih
                                                </label>

                                            <!-- CHECKBOX GROUP Pilihan Ganda -->
                                            @elseif($field->type === 'checkbox_group')
                                                <div class="flex flex-wrap gap-4 pt-1">
                                                    @if($field->options)
                                                        @foreach($field->options as $opt)
                                                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-not-allowed select-none">
                                                                <input type="checkbox" disabled class="rounded border-slate-300 text-[#173D8F]">
                                                                {{ $opt }}
                                                            </label>
                                                        @endforeach
                                                    @else
                                                        <span class="text-xs text-slate-400 italic">Opsi belum didefinisikan</span>
                                                    @endif
                                                </div>

                                            <!-- TABLE Dinamis -->
                                            @elseif($field->type === 'table' && isset($field->config['columns']))
                                                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                                                    <table class="w-full text-xs text-left border-collapse bg-white">
                                                        <thead>
                                                            <tr class="bg-slate-50 border-b border-slate-200 text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                                                <th class="py-2 px-3 w-10 text-center">Pilih</th>
                                                                <th class="py-2 px-3 w-1/4">{{ $field->config['columns'][0] }}</th>
                                                                <th class="py-2 px-3">{{ $field->config['columns'][1] ?? 'Detail Dampak' }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100">
                                                            @if(isset($field->config['rows']))
                                                                @foreach($field->config['rows'] as $row)
                                                                    <tr>
                                                                        <td class="py-2 px-3 text-center">
                                                                            <input type="checkbox" disabled class="rounded border-slate-300 text-[#173D8F]">
                                                                        </td>
                                                                        <td class="py-2 px-3 font-bold text-slate-700">{{ $row['label'] }}</td>
                                                                        <td class="py-1.5 px-3">
                                                                            <input type="text" disabled class="w-full px-2 py-1 bg-slate-50 border border-slate-200 rounded-md text-xs cursor-not-allowed" placeholder="Penjelasan dampak...">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Signature Box Preview / Dynamic Approvals -->
                    @php
                        $approvalSection = $template->sections->first(fn($s) => str_contains(strtolower($s->title), 'approval'));
                        $approvalFields = $approvalSection ? $approvalSection->fields()->orderBy('order')->get() : collect();
                    @endphp

                    @if($approvalFields->count())
                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                {{ $approvalSection->title }}
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($approvalFields as $fld)
                                    @php
                                        $isPemohonStep = ($fld->config['jenis_approval'] ?? '') === 'pemohon';
                                        $approverTitle = $fld->config['group'] ?? 'Approval';
                                        $approverName = $fld->config['approver_name'] ?? '-';
                                        $approverPosition = $fld->config['approver_position'] ?? '-';
                                        $subtitle = $fld->config['subtitle'] ?? '';
                                    @endphp
                                    <div class="border border-slate-200 rounded-xl p-3 text-center bg-slate-50/50 flex flex-col justify-between h-36">
                                        <div class="space-y-0.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">{{ $approverTitle }}</span>
                                            <span class="text-[10px] font-bold text-slate-700 block truncate" title="{{ $subtitle }}">{{ $subtitle }}</span>
                                        </div>
                                        
                                        <!-- Signature Placeholder -->
                                        <div class="py-2 flex items-center justify-center">
                                            <div class="text-[9px] text-slate-400 border border-dashed border-slate-300 rounded px-2 py-1 bg-white font-mono uppercase tracking-wide select-none">
                                                Digital Signature
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-0.5">
                                            <span class="text-[10px] font-bold text-slate-800 block underline truncate" title="{{ $approverName }}">{{ $approverName }}</span>
                                            <span class="text-[9px] text-slate-500 block truncate" title="{{ $approverPosition }}">{{ $approverPosition }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>

    </div>
</div>

<!-- ==========================================
      MODALS
     ========================================== -->

<!-- ADD FIELD MODAL -->
<div id="add-field-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg overflow-hidden transition-all transform scale-95 duration-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span class="p-1 rounded-lg bg-blue-50 text-[#173D8F]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </span>
                Tambah Field Baru
            </h3>
            <button type="button" onclick="closeAddFieldModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body / Form -->
        <form id="add-field-form" onsubmit="submitAddFieldForm(event)" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Label Field -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Label Field</label>
                    <input type="text" name="label" required placeholder="Contoh: Deskripsi Perubahan"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                </div>

                <!-- Tipe Field -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Tipe Field</label>
                    <select name="type" id="add-type" required onchange="handleTypeChange('add')"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                        <option value="text">Text Input (Satu Baris)</option>
                        <option value="textarea">Textarea (Banyak Baris)</option>
                        <option value="checkbox">Checkbox (Centang Tunggal)</option>
                        <option value="checkbox_group">Checkbox Group (Banyak Centang)</option>
                        <option value="table">Tabel Dinamis</option>
                    </select>
                </div>
            </div>

            <!-- Required -->
            <div class="flex items-center gap-2 py-1">
                <input type="checkbox" id="add-is-required" name="is_required" value="1"
                       class="rounded border-slate-300 text-[#173D8F] focus:ring-[#173D8F]/20">
                <label for="add-is-required" class="text-xs font-semibold text-slate-700 cursor-pointer select-none">Wajib diisi (Required)</label>
            </div>

            <!-- Placeholder -->
            <div id="add-placeholder-container" class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Placeholder</label>
                <input type="text" name="placeholder" placeholder="Masukkan placeholder pembantu..."
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
            </div>

            <!-- Checkbox Group Options -->
            <div id="add-options-container" class="space-y-1 hidden">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pilihan Opsi (pisahkan dengan koma)</label>
                <input type="text" name="options" placeholder="Contoh: High, Medium, Low"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
            </div>

            <!-- Table Config -->
            <div id="add-table-container" class="space-y-3 hidden">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Kolom Tabel (pisahkan dengan koma)</label>
                    <input type="text" name="table_columns" placeholder="Contoh: Dampak, Penjelasan Dampak"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Baris Tabel (pisahkan dengan koma)</label>
                    <input type="text" name="table_rows" placeholder="Contoh: Biaya, Waktu, Lainnya"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                </div>
            </div>

            <!-- Approval Settings (only shown if in approval section) -->
            <div id="add-approval-container" class="border-t border-slate-100 pt-3.5 space-y-4 hidden">
                <span class="text-[10px] font-bold text-[#173D8F] uppercase tracking-wider block">Konfigurasi Approval</span>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Jenis Approval</label>
                        <select name="jenis_approval" id="add-jenis-approval" onchange="toggleJenisApproval('add')"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                            <option value="user_tertentu">User Tertentu</option>
                            <option value="pemohon">Pemohon</option>
                        </select>
                    </div>

                    <!-- Autocomplete search -->
                    <div id="add-approver-select-container" class="space-y-1 relative">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pilih Approver</label>
                        <input type="text" id="add-approver-search" onkeyup="searchUsers(this, 'add')" 
                               placeholder="Ketik Nama, Email, atau Jabatan..." 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                        
                        <div id="add-approver-suggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-40 overflow-y-auto">
                            <!-- JS autocomplete items -->
                        </div>

                        <!-- Hidden user info fields -->
                        <input type="hidden" name="approver_user_id" id="add-approver-id">
                        <input type="hidden" name="approver_name" id="add-approver-name">
                        <input type="hidden" name="approver_position" id="add-approver-position">
                        <input type="hidden" name="approver_email" id="add-approver-email">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Group Approval</label>
                        <input type="text" name="group" value="Approval"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Posisi Grid</label>
                        <select name="position"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                            <option value="left">Kiri (Left)</option>
                            <option value="right">Kanan (Right)</option>
                            <option value="center">Tengah (Center)</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Subtitle/Jabatan</label>
                        <input type="text" name="subtitle" placeholder="Contoh: Senior Manager"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Footer / Buttons -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddFieldModal()"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition-colors">
                    Batal
                </button>
                <button type="submit" id="add-field-submit-btn"
                        class="px-4 py-2 bg-[#173D8F] hover:bg-opacity-95 text-white font-semibold rounded-xl text-xs shadow-xs transition-all">
                    Tambah
                </button>
            </div>

        </form>
    </div>
</div>

<!-- EDIT FIELD MODAL -->
<div id="edit-field-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg overflow-hidden transition-all transform scale-95 duration-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span class="p-1 rounded-lg bg-blue-50 text-[#173D8F]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </span>
                Edit Field
            </h3>
            <button type="button" onclick="closeEditFieldModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body / Form -->
        <form id="edit-field-form" method="POST" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Label Field -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Label Field</label>
                    <input type="text" name="label" id="edit-label" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                </div>

                <!-- Tipe Field -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Tipe Field</label>
                    <select name="type" id="edit-type" required onchange="handleTypeChange('edit')"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                        <option value="text">Text Input (Satu Baris)</option>
                        <option value="textarea">Textarea (Banyak Baris)</option>
                        <option value="checkbox">Checkbox (Centang Tunggal)</option>
                        <option value="checkbox_group">Checkbox Group (Banyak Centang)</option>
                        <option value="table">Tabel Dinamis</option>
                    </select>
                </div>
            </div>

            <!-- Required -->
            <div class="flex items-center gap-2 py-1">
                <input type="checkbox" id="edit-is-required" name="is_required" value="1"
                       class="rounded border-slate-300 text-[#173D8F] focus:ring-[#173D8F]/20">
                <label for="edit-is-required" class="text-xs font-semibold text-slate-700 cursor-pointer select-none">Wajib diisi (Required)</label>
            </div>

            <!-- Order -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Order/Urutan</label>
                <input type="number" name="order" id="edit-order" required
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
            </div>

            <!-- Placeholder -->
            <div id="edit-placeholder-container" class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Placeholder</label>
                <input type="text" name="placeholder" id="edit-placeholder"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
            </div>

            <!-- Checkbox Group Options -->
            <div id="edit-options-container" class="space-y-1 hidden">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pilihan Opsi (pisahkan dengan koma)</label>
                <input type="text" name="options" id="edit-options"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
            </div>

            <!-- Table Config -->
            <div id="edit-table-container" class="space-y-3 hidden">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Kolom Tabel (pisahkan dengan koma)</label>
                    <input type="text" name="table_columns" id="edit-table-columns"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Baris Tabel (pisahkan dengan koma)</label>
                    <input type="text" name="table_rows" id="edit-table-rows"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                </div>
            </div>

            <!-- Approval Settings (only shown if in approval section) -->
            <div id="edit-approval-container" class="border-t border-slate-100 pt-3.5 space-y-4 hidden">
                <span class="text-[10px] font-bold text-[#173D8F] uppercase tracking-wider block">Konfigurasi Approval</span>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Jenis Approval</label>
                        <select name="jenis_approval" id="edit-jenis-approval" onchange="toggleJenisApproval('edit')"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                            <option value="user_tertentu">User Tertentu</option>
                            <option value="pemohon">Pemohon</option>
                        </select>
                    </div>

                    <!-- Autocomplete search -->
                    <div id="edit-approver-select-container" class="space-y-1 relative">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pilih Approver</label>
                        <input type="text" id="edit-approver-search" onkeyup="searchUsers(this, 'edit')" 
                               placeholder="Ketik Nama, Email, atau Jabatan..." 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                        
                        <div id="edit-approver-suggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-50 max-h-40 overflow-y-auto">
                            <!-- JS autocomplete items -->
                        </div>

                        <!-- Hidden user info fields -->
                        <input type="hidden" name="approver_user_id" id="edit-approver-id">
                        <input type="hidden" name="approver_name" id="edit-approver-name">
                        <input type="hidden" name="approver_position" id="edit-approver-position">
                        <input type="hidden" name="approver_email" id="edit-approver-email">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Group Approval</label>
                        <input type="text" name="group" id="edit-group"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Posisi Grid</label>
                        <select name="position" id="edit-position"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                            <option value="left">Kiri (Left)</option>
                            <option value="right">Kanan (Right)</option>
                            <option value="center">Tengah (Center)</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Subtitle/Jabatan</label>
                        <input type="text" name="subtitle" id="edit-subtitle"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-xs focus:border-[#173D8F] focus:ring-1 focus:ring-[#173D8F]/20 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Footer / Buttons -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditFieldModal()"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-[#173D8F] hover:bg-opacity-95 text-white font-semibold rounded-xl text-xs shadow-xs transition-all">
                    Perbarui Kolom
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ==========================================
      SCRIPTS
     ========================================== -->
<script>
    const allUsers = @json($users ?? []);
    let activeSectionId = null;
    let activeSectionTitle = "";

    // ──────────────────────────────────────────────
    // MODAL OPEN / CLOSE HANDLERS
    // ──────────────────────────────────────────────

    function openAddFieldModal(sectionId, sectionTitle) {
        activeSectionId = sectionId;
        activeSectionTitle = sectionTitle;
        
        const modal = document.getElementById('add-field-modal');
        const form = document.getElementById('add-field-form');
        
        // Reset form
        form.reset();
        
        // Default visibility states
        handleTypeChange('add');
        
        // Set Approval block visibility
        const approvalContainer = document.getElementById('add-approval-container');
        if (sectionTitle.toLowerCase().includes('approval')) {
            approvalContainer.classList.remove('hidden');
        } else {
            approvalContainer.classList.add('hidden');
        }
        
        // Open modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.firstElementChild.classList.remove('scale-95');
        }, 10);
    }

    function closeAddFieldModal() {
        const modal = document.getElementById('add-field-modal');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    function openEditFieldModal(fieldObj, sectionTitle) {
        const modal = document.getElementById('edit-field-modal');
        const form = document.getElementById('edit-field-form');
        
        // Set Form Action URL dynamically
        form.action = `/admin/fields/${fieldObj.id}`;
        
        // Populate inputs
        document.getElementById('edit-label').value = fieldObj.label || '';
        document.getElementById('edit-type').value = fieldObj.type || 'text';
        document.getElementById('edit-is-required').checked = !!fieldObj.is_required;
        document.getElementById('edit-order').value = fieldObj.order || 0;
        
        // Extract config details
        const config = fieldObj.config || {};
        document.getElementById('edit-placeholder').value = config.placeholder || '';
        
        // Populate Options
        document.getElementById('edit-options').value = fieldObj.options ? fieldObj.options.join(', ') : '';
        
        // Populate Table Columns/Rows
        document.getElementById('edit-table-columns').value = config.columns ? config.columns.join(', ') : '';
        document.getElementById('edit-table-rows').value = config.rows ? config.rows.map(r => r.label).join(', ') : '';
        
        // Show/Hide inputs based on type
        handleTypeChange('edit');
        
        // Populate Approval Fields
        const approvalContainer = document.getElementById('edit-approval-container');
        if (sectionTitle.toLowerCase().includes('approval')) {
            approvalContainer.classList.remove('hidden');
            
            const jenisApp = config.jenis_approval || 'user_tertentu';
            document.getElementById('edit-jenis-approval').value = jenisApp;
            
            document.getElementById('edit-approver-id').value = config.approver_user_id || '';
            document.getElementById('edit-approver-name').value = config.approver_name || '';
            document.getElementById('edit-approver-position').value = config.approver_position || '';
            document.getElementById('edit-approver-email').value = config.approver_email || '';
            
            const appSearchVal = config.approver_name ? `${config.approver_name} (${config.approver_position || ''})` : '';
            document.getElementById('edit-approver-search').value = appSearchVal;
            
            toggleJenisApproval('edit');
            
            document.getElementById('edit-group').value = config.group || 'Approval';
            document.getElementById('edit-position').value = config.position || 'left';
            document.getElementById('edit-subtitle').value = config.subtitle || '';
        } else {
            approvalContainer.classList.add('hidden');
        }
        
        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.firstElementChild.classList.remove('scale-95');
        }, 10);
    }

    function closeEditFieldModal() {
        const modal = document.getElementById('edit-field-modal');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    // ──────────────────────────────────────────────
    // FORM BEHAVIOR INTERACTION
    // ──────────────────────────────────────────────

    function handleTypeChange(modalType) {
        const typeSelect = document.getElementById(`${modalType}-type`);
        const value = typeSelect.value;
        
        const placeholderContainer = document.getElementById(`${modalType}-placeholder-container`);
        const optionsContainer = document.getElementById(`${modalType}-options-container`);
        const tableContainer = document.getElementById(`${modalType}-table-container`);
        
        // Table columns/rows are only for 'table' type
        if (value === 'table') {
            tableContainer.classList.remove('hidden');
            placeholderContainer.classList.add('hidden');
            optionsContainer.classList.add('hidden');
        } 
        // Options are only for checkbox_group
        else if (value === 'checkbox_group') {
            optionsContainer.classList.remove('hidden');
            placeholderContainer.classList.add('hidden');
            tableContainer.classList.add('hidden');
        }
        // Text/Textarea support placeholder
        else if (value === 'text' || value === 'textarea') {
            placeholderContainer.classList.remove('hidden');
            optionsContainer.classList.add('hidden');
            tableContainer.classList.add('hidden');
        } 
        // Checkbox has no config options
        else {
            placeholderContainer.classList.add('hidden');
            optionsContainer.classList.add('hidden');
            tableContainer.classList.add('hidden');
        }
    }

    function toggleJenisApproval(modalType) {
        const jenisSelect = document.getElementById(`${modalType}-jenis-approval`);
        const container = document.getElementById(`${modalType}-approver-select-container`);
        
        if (jenisSelect.value === 'pemohon') {
            container.classList.add('hidden');
        } else {
            container.classList.remove('hidden');
        }
    }

    // ──────────────────────────────────────────────
    // APPROVER AUTOCOMPLETE / SUGGESTIONS
    // ──────────────────────────────────────────────

    function searchUsers(inputEl, modalType) {
        const query = inputEl.value.toLowerCase().trim();
        const suggestions = document.getElementById(`${modalType}-approver-suggestions`);
        
        if (!query) {
            suggestions.innerHTML = '';
            suggestions.classList.add('hidden');
            return;
        }
        
        const filtered = allUsers.filter(u => {
            return (u.name && u.name.toLowerCase().includes(query)) ||
                   (u.email && u.email.toLowerCase().includes(query)) ||
                   (u.position && u.position.toLowerCase().includes(query));
        });
        
        if (filtered.length === 0) {
            suggestions.innerHTML = '<div class="p-2 text-xs text-slate-400">Tidak ada user ditemukan</div>';
            suggestions.classList.remove('hidden');
            return;
        }
        
        let html = '';
        filtered.forEach(u => {
            const uName = (u.name || '').replace(/'/g, "\\'");
            const uPos = (u.position || '').replace(/'/g, "\\'");
            const uEmail = (u.email || '').replace(/'/g, "\\'");
            
            html += `
                <div onclick="selectUser('${modalType}', ${u.id}, '${uName}', '${uPos}', '${uEmail}')" 
                    class="p-2 hover:bg-slate-50 cursor-pointer text-xs border-b border-slate-100 last:border-b-0 text-left">
                    <div class="font-bold text-slate-800">${u.name}</div>
                    <div class="text-[10px] text-slate-500">${u.position || '-'} &bull; ${u.email}</div>
                </div>
            `;
        });
        
        suggestions.innerHTML = html;
        suggestions.classList.remove('hidden');
    }

    function selectUser(modalType, id, name, position, email) {
        document.getElementById(`${modalType}-approver-id`).value = id;
        document.getElementById(`${modalType}-approver-name`).value = name;
        document.getElementById(`${modalType}-approver-position`).value = position;
        document.getElementById(`${modalType}-approver-email`).value = email;
        
        document.getElementById(`${modalType}-approver-search`).value = `${name} (${position})`;
        
        const suggestions = document.getElementById(`${modalType}-approver-suggestions`);
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');
    }

    // Dismiss suggestions dropdown when clicking outside
    document.addEventListener('click', function(e) {
        ['add', 'edit'].forEach(modalType => {
            const suggestions = document.getElementById(`${modalType}-approver-suggestions`);
            const searchInput = document.getElementById(`${modalType}-approver-search`);
            if (suggestions && searchInput && !suggestions.contains(e.target) && e.target !== searchInput) {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
            }
        });
    });

    // ──────────────────────────────────────────────
    // AJAX DOUBLE SAVE FOR FIELD CREATION
    // ──────────────────────────────────────────────

    function submitAddFieldForm(e) {
        e.preventDefault();
        
        const formEl = document.getElementById('add-field-form');
        const submitBtn = document.getElementById('add-field-submit-btn');
        const originalText = submitBtn.innerText;
        
        submitBtn.innerText = "Menyimpan...";
        submitBtn.disabled = true;
        
        const label = formEl.elements['label'].value;
        const type = formEl.elements['type'].value;
        const token = formEl.elements['_token'].value;
        
        // 1. Post creation to store endpoint
        const storeUrl = `/admin/sections/${activeSectionId}/fields`;
        
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('label', label);
        formData.append('type', type);
        
        fetch(storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal membuat field baru di database.');
            return response.text();
        })
        .then(htmlText => {
            // Parse response HTML to find the ID of the new field
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            
            // Find IDs on current page
            const currentIds = Array.from(document.querySelectorAll('[id^="field-card-"]')).map(el => el.id.replace('field-card-', ''));
            // Find IDs on returned page
            const parsedIds = Array.from(doc.querySelectorAll('[id^="field-card-"]')).map(el => el.id.replace('field-card-', ''));
            
            // The newly created field ID is the one that exists in the response HTML but not on current page
            const newFieldId = parsedIds.find(id => !currentIds.includes(id));
            
            if (!newFieldId) {
                // If we cannot find it, it means maybe there were no extra values to save, just reload
                window.location.reload();
                return;
            }
            
            // 2. Perform PUT request to save additional fields (is_required, placeholder, options, etc.)
            const updateUrl = `/admin/fields/${newFieldId}`;
            const putData = new URLSearchParams();
            putData.append('_token', token);
            putData.append('_method', 'PUT');
            putData.append('label', label);
            putData.append('type', type);
            
            if (formEl.elements['is_required'].checked) {
                putData.append('is_required', '1');
            }
            
            // Options
            putData.append('options', formEl.elements['options'].value);
            
            // Placeholder
            putData.append('placeholder', formEl.elements['placeholder'].value);
            
            // Table parameters
            putData.append('table_columns', formEl.elements['table_columns'].value);
            putData.append('table_rows', formEl.elements['table_rows'].value);
            
            // Approval parameters if section is approval
            if (activeSectionTitle.toLowerCase().includes('approval')) {
                putData.append('jenis_approval', formEl.elements['jenis_approval'].value);
                putData.append('approver_user_id', formEl.elements['approver_user_id'].value);
                putData.append('approver_name', formEl.elements['approver_name'].value);
                putData.append('approver_position', formEl.elements['approver_position'].value);
                putData.append('approver_email', formEl.elements['approver_email'].value);
                putData.append('group', formEl.elements['group'].value);
                putData.append('position', formEl.elements['position'].value);
                putData.append('subtitle', formEl.elements['subtitle'].value);
            }
            
            return fetch(updateUrl, {
                method: 'POST', // URLSearchParams handles putting _method: PUT inside body
                body: putData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        })
        .then(updateResponse => {
            // Field created and updated successfully, reload page
            window.location.reload();
        })
        .catch(err => {
            alert(err.message || 'Terjadi kesalahan sistem.');
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        });
    }
</script>
@endsection
