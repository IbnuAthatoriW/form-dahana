@extends('layouts.admin')

@section('page_title', 'Master Template Builder')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Template
        </a>
        <a href="{{ route('form.fill', $template->id) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
            Lihat Preview Form Aktif
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    </div>

    <!-- Main Builder Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left Side: Metadata and Section Addition -->
        <div class="space-y-6">
            <!-- Metadata Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 title-font border-b border-slate-50 pb-3 mb-4">Metadata Form</h3>
                
                <form action="{{ route('admin.templates.update', $template->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Judul Form</label>
                        <input type="text" name="title" value="{{ old('title', $template->title) }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-xs focus:border-blue-500 transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Author</label>
                        <input type="text" name="author" value="{{ old('author', $template->author) }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-xs focus:border-blue-500 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</label>
                            <input type="text" name="status" value="{{ old('status', $template->status) }}" required
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-xs focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Revisi</label>
                            <input type="text" name="revision" value="{{ old('revision', $template->revision) }}" required
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-xs focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pengantar</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-xs focus:border-blue-500 transition-all">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                        <label for="is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">Aktifkan Template Form ini</label>
                    </div>

                    <button type="submit" class="w-full py-2 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-lg text-xs transition-colors shadow-sm">
                        Perbarui Metadata
                    </button>
                </form>
            </div>

            <!-- Add Section Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 title-font border-b border-slate-50 pb-3 mb-4">Tambah Bagian (Section)</h3>
                
                <form action="{{ route('admin.templates.sections.store', $template->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Judul Bagian</label>
                        <input type="text" name="title" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white text-xs focus:border-blue-500 transition-all"
                               placeholder="Contoh: 2. Detail Perubahan">
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition-colors border border-slate-200">
                        + Tambah Bagian
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Sections & Fields Builder -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Static Section Info Card -->
            <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-300 p-6 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-800 block uppercase tracking-wider">1. Section Identitas (Statis)</span>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Bagian header identitas dan detail pemohon/peruntukan (Nama, Jabatan, Departemen, Tanggal Pengajuan, SLA) sudah terpasang secara otomatis dan statis pada form, tidak perlu dikonfigurasi secara manual.
                    </p>
                </div>
            </div>

            <!-- Dynamic Sections Builder -->
            @if($template->sections->isEmpty())
                <div class="bg-white p-12 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <h4 class="text-sm font-bold text-slate-700">Belum ada bagian formulir</h4>
                    <p class="text-xs text-slate-500 mt-1">Gunakan panel sebelah kiri untuk menambahkan bagian baru.</p>
                </div>
            @else
                @foreach($template->sections as $sec)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                        <!-- Section Header -->
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <form action="{{ route('admin.templates.sections.update', $sec->id) }}" method="POST" class="flex items-center gap-3">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" value="{{ $sec->title }}" class="text-base font-bold text-slate-800 bg-transparent border-b border-transparent focus:border-slate-300 focus:outline-none px-1 py-0.5" title="Klik untuk mengubah judul">
                                <input type="number" name="order" value="{{ $sec->order }}" class="w-12 text-center text-xs bg-slate-50 border border-slate-200 rounded-md py-0.5" title="Order">
                                <button type="submit" class="text-[10px] text-blue-600 hover:text-blue-700 font-semibold uppercase">Simpan</button>
                            </form>

                            <form action="{{ route('admin.templates.sections.destroy', $sec->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bagian ini? Semua kolom di dalamnya akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-medium">Hapus Bagian</button>
                            </form>
                        </div>

                        <!-- Fields List in Section -->
                        <div class="space-y-4">
                            @if($sec->fields->isEmpty())
                                <p class="text-xs text-slate-400 italic text-center py-4">Belum ada kolom di bagian ini.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($sec->fields as $fld)
                                        <div class="bg-slate-50/50 hover:bg-slate-50 border border-slate-200/65 rounded-xl p-4 transition-colors">
                                            <!-- Field header / View mode -->
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="space-y-1">
                                                    <span class="text-xs font-bold text-slate-800 flex items-center gap-2">
                                                        {{ $fld->label }}
                                                        @if($fld->is_required)
                                                            <span class="text-red-500 font-bold text-[10px] font-sans">* Wajib</span>
                                                        @endif
                                                    </span>
                                                    <div class="flex gap-2 items-center">
                                                        <span class="text-[10px] font-bold px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-md uppercase tracking-wider">
                                                            {{ $fld->type }}
                                                        </span>
                                                        @if($fld->type === 'checkbox_group' && $fld->options)
                                                            <span class="text-[10px] text-slate-500">Pilihan: {{ implode(', ', $fld->options) }}</span>
                                                        @endif
                                                        @if($fld->type === 'table' && isset($fld->config['columns']))
                                                            <span class="text-[10px] text-slate-500">Tabel: [{{ implode(' | ', $fld->config['columns']) }}]</span>
                                                        @endif
                                                        @if(isset($fld->config['group']))
                                                            <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 rounded px-1.5 py-0.5">Approval: {{ $fld->config['group'] }} ({{ $fld->config['position'] }})</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Field Actions -->
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <button type="button" onclick="document.getElementById('edit-field-{{ $fld->id }}').classList.toggle('hidden')" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('admin.templates.fields.destroy', $fld->id) }}" method="POST" onsubmit="return confirm('Hapus kolom ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-medium">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Field Quick Edit Form (Hidden by default) -->
                                            <div id="edit-field-{{ $fld->id }}" class="hidden mt-4 pt-4 border-t border-slate-200/80 space-y-4">
                                                <form action="{{ route('admin.templates.fields.update', $fld->id) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="space-y-1">
                                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama/Label Kolom</label>
                                                            <input type="text" name="label" value="{{ $fld->label }}" required
                                                                   class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                        </div>

                                                        <div class="space-y-1">
                                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tipe Kolom</label>
                                                            <select name="type" required onchange="toggleFieldOptionsEdit(this, '{{ $fld->id }}')"
                                                                    class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                                <option value="text" {{ $fld->type === 'text' ? 'selected' : '' }}>Text Input (Satu Baris)</option>
                                                                <option value="textarea" {{ $fld->type === 'textarea' ? 'selected' : '' }}>Textarea (Banyak Baris)</option>
                                                                <option value="checkbox" {{ $fld->type === 'checkbox' ? 'selected' : '' }}>Checkbox (Centang Tunggal)</option>
                                                                <option value="checkbox_group" {{ $fld->type === 'checkbox_group' ? 'selected' : '' }}>Checkbox Group (Banyak Centang)</option>
                                                                <option value="table" {{ $fld->type === 'table' ? 'selected' : '' }}>Tabel Dinamis</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Order and Required -->
                                                    <div class="flex items-center gap-6">
                                                        <div class="flex items-center gap-1.5">
                                                            <input type="checkbox" id="req-{{ $fld->id }}" name="is_required" value="1" {{ $fld->is_required ? 'checked' : '' }}
                                                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                                                            <label for="req-{{ $fld->id }}" class="text-xs text-slate-700 cursor-pointer">Wajib diisi</label>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <label class="text-xs text-slate-500">Order/Urutan:</label>
                                                            <input type="number" name="order" value="{{ $fld->order }}" class="w-12 text-center text-xs bg-white border border-slate-200 rounded-md py-0.5">
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group Options Edit -->
                                                    <div id="options-container-edit-{{ $fld->id }}" class="{{ $fld->type === 'checkbox_group' ? '' : 'hidden' }} space-y-1">
                                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Opsi Pilihan (pisahkan dengan koma)</label>
                                                        <input type="text" name="options" value="{{ $fld->options ? implode(', ', $fld->options) : '' }}"
                                                               class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all"
                                                               placeholder="Contoh: High, Medium, Low">
                                                    </div>

                                                    <!-- Table Columns & Rows Edit -->
                                                    <div id="table-container-edit-{{ $fld->id }}" class="{{ $fld->type === 'table' ? '' : 'hidden' }} space-y-3">
                                                        <div class="space-y-1">
                                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Kolom Tabel (pisahkan dengan koma)</label>
                                                            <input type="text" name="table_columns" value="{{ isset($fld->config['columns']) ? implode(', ', $fld->config['columns']) : '' }}"
                                                                   class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all"
                                                                   placeholder="Contoh: Dampak, Penjelasan Dampak">
                                                        </div>
                                                        <div class="space-y-1">
                                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Nama Baris Tabel (pisahkan dengan koma)</label>
                                                            <input type="text" name="table_rows" value="{{ isset($fld->config['rows']) ? implode(', ', array_column($fld->config['rows'], 'label')) : '' }}"
                                                                   class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all"
                                                                   placeholder="Contoh: Biaya, Waktu, Lainnya">
                                                        </div>
                                                    </div>

                                                     <!-- Approval Settings Edit (Only visible if fields is in approval section) -->
                                                     @if(str_contains(strtolower($sec->title), 'approval'))
                                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-3">
                                                             <div class="space-y-1">
                                                                 <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Jenis Approval</label>
                                                                 <select name="jenis_approval" onchange="toggleJenisApproval(this, '{{ $fld->id }}')" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                                     <option value="user_tertentu" {{ ($fld->config['jenis_approval'] ?? 'user_tertentu') === 'user_tertentu' ? 'selected' : '' }}>User Tertentu</option>
                                                                     <option value="pemohon" {{ ($fld->config['jenis_approval'] ?? '') === 'pemohon' ? 'selected' : '' }}>Pemohon</option>
                                                                 </select>
                                                             </div>

                                                             <!-- User Autocomplete Input -->
                                                             <div id="approver-select-container-{{ $fld->id }}" class="{{ ($fld->config['jenis_approval'] ?? '') === 'pemohon' ? 'hidden' : '' }} space-y-1 relative">
                                                                 <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pilih Approver</label>
                                                                 <input type="text" id="approver-search-{{ $fld->id }}" onkeyup="searchUsers(this, '{{ $fld->id }}')" 
                                                                     placeholder="Ketik Nama, Email, atau Jabatan..." 
                                                                     value="{{ !empty($fld->config['approver_name']) ? $fld->config['approver_name'] . ' (' . ($fld->config['approver_position'] ?? '') . ')' : '' }}"
                                                                     class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                                 
                                                                 <!-- Suggestions dropdown -->
                                                                 <div id="approver-suggestions-{{ $fld->id }}" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg z-50 max-h-48 overflow-y-auto">
                                                                     <!-- items generated via JS -->
                                                                 </div>

                                                                 <!-- Hidden fields for user info -->
                                                                 <input type="hidden" name="approver_user_id" id="approver-id-{{ $fld->id }}" value="{{ $fld->config['approver_user_id'] ?? '' }}">
                                                                 <input type="hidden" name="approver_name" id="approver-name-{{ $fld->id }}" value="{{ $fld->config['approver_name'] ?? '' }}">
                                                                 <input type="hidden" name="approver_position" id="approver-position-{{ $fld->id }}" value="{{ $fld->config['approver_position'] ?? '' }}">
                                                                 <input type="hidden" name="approver_email" id="approver-email-{{ $fld->id }}" value="{{ $fld->config['approver_email'] ?? '' }}">
                                                             </div>
                                                         </div>

                                                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3">
                                                             <div class="space-y-1">
                                                                 <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Group Approval</label>
                                                                 <input type="text" name="group" value="{{ $fld->config['group'] ?? 'Approval' }}" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                             </div>
                                                             <div class="space-y-1">
                                                                 <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Posisi Grid</label>
                                                                 <select name="position" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                                     <option value="left" {{ ($fld->config['position'] ?? 'left') === 'left' ? 'selected' : '' }}>Kiri (Left)</option>
                                                                     <option value="right" {{ ($fld->config['position'] ?? '') === 'right' ? 'selected' : '' }}>Kanan (Right)</option>
                                                                     <option value="center" {{ ($fld->config['position'] ?? '') === 'center' ? 'selected' : '' }}>Tengah (Center - Full width)</option>
                                                                 </select>
                                                             </div>
                                                             <div class="space-y-1">
                                                                 <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Subtitle/Jabatan</label>
                                                                 <input type="text" name="subtitle" value="{{ $fld->config['subtitle'] ?? '' }}" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:border-blue-500 transition-all">
                                                             </div>
                                                         </div>
                                                     @endif

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" onclick="document.getElementById('edit-field-{{ $fld->id }}').classList.add('hidden')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs transition-colors">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs transition-colors">
                                                            Perbarui Kolom
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Add Field to Section Form -->
                        <div class="border-t border-slate-100 pt-4 bg-slate-50/20 -mx-6 -mb-6 p-6 rounded-b-2xl">
                            <span class="text-xs font-bold text-slate-700 uppercase block mb-3">+ Tambah Kolom Baru ke Bagian Ini</span>
                            
                            <form action="{{ route('admin.templates.fields.store', $sec->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                @csrf
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Label/Nama Kolom</label>
                                    <input type="text" name="label" required
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:border-blue-500 transition-all text-xs"
                                           placeholder="Contoh: Deskripsi Perubahan">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Tipe Kolom</label>
                                    <select name="type" required onchange="toggleFieldOptionsAdd(this, '{{ $sec->id }}')"
                                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:border-blue-500 transition-all text-xs">
                                        <option value="text">Text Input (Satu Baris)</option>
                                        <option value="textarea">Textarea (Banyak Baris)</option>
                                        <option value="checkbox">Checkbox (Centang Tunggal)</option>
                                        <option value="checkbox_group">Checkbox Group (Banyak Centang)</option>
                                        <option value="table">Tabel Dinamis</option>
                                    </select>
                                </div>

                                <button type="submit" class="py-2 px-4 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg text-xs transition-colors border border-transparent shadow-xs">
                                    Tambah Kolom
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
    const allUsers = @json($users ?? []);

    function toggleFieldOptionsEdit(selectEl, fieldId) {
        const value = selectEl.value;
        const optionsContainer = document.getElementById(`options-container-edit-${fieldId}`);
        const tableContainer = document.getElementById(`table-container-edit-${fieldId}`);
        
        if (value === 'checkbox_group') {
            optionsContainer.classList.remove('hidden');
            tableContainer.classList.add('hidden');
        } else if (value === 'table') {
            tableContainer.classList.remove('hidden');
            optionsContainer.classList.add('hidden');
        } else {
            optionsContainer.classList.add('hidden');
            tableContainer.classList.add('hidden');
        }
    }

    function toggleJenisApproval(selectEl, fieldId) {
        const container = document.getElementById(`approver-select-container-${fieldId}`);
        if (selectEl.value === 'pemohon') {
            container.classList.add('hidden');
        } else {
            container.classList.remove('hidden');
        }
    }

    function searchUsers(inputEl, fieldId) {
        const query = inputEl.value.toLowerCase().trim();
        const suggestions = document.getElementById(`approver-suggestions-${fieldId}`);
        
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
                <div onclick="selectUser('${fieldId}', ${u.id}, '${uName}', '${uPos}', '${uEmail}')" 
                    class="p-2 hover:bg-slate-50 cursor-pointer text-xs border-b border-slate-100 last:border-b-0">
                    <div class="font-bold text-slate-800">${u.name}</div>
                    <div class="text-[10px] text-slate-500">${u.position || '-'} &bull; ${u.email}</div>
                </div>
            `;
        });
        
        suggestions.innerHTML = html;
        suggestions.classList.remove('hidden');
    }

    function selectUser(fieldId, id, name, position, email) {
        document.getElementById(`approver-id-${fieldId}`).value = id;
        document.getElementById(`approver-name-${fieldId}`).value = name;
        document.getElementById(`approver-position-${fieldId}`).value = position;
        document.getElementById(`approver-email-${fieldId}`).value = email;
        
        document.getElementById(`approver-search-${fieldId}`).value = `${name} (${position})`;
        
        const suggestions = document.getElementById(`approver-suggestions-${fieldId}`);
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');
    }

    // Dismiss suggestions dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('[id^="approver-suggestions-"]');
        dropdowns.forEach(d => {
            const fieldId = d.id.replace('approver-suggestions-', '');
            const searchInput = document.getElementById(`approver-search-${fieldId}`);
            if (searchInput && !d.contains(e.target) && e.target !== searchInput) {
                d.innerHTML = '';
                d.classList.add('hidden');
            }
        });
    });
</script>
@endsection
