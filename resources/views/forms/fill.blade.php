@extends('layouts.app')

@section('title', $template->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header info -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors mb-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Portal
    </a>

    <!-- Form Container -->
    <form action="{{ route('form.store', $template->id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-12 space-y-8">
        @csrf

        <!-- Logo and Form Header Table -->
        <div class="border border-slate-300 rounded-lg overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-300">
                <!-- Logo Dahana -->
                <div class="p-6 flex items-center justify-center bg-slate-50/50">
                    <img
                        src="{{ asset('images/logo-dahana.png') }}"
                        alt="PT Dahana"
                        class="h-10 w-auto">
                </div>
                <!-- Title Form -->
                <div class="md:col-span-2 p-6 flex flex-col justify-center text-center">
                    <h2 class="text-base font-extrabold text-slate-800 tracking-tight leading-snug">
                        Form Pengajuan Change Request<br>Infrastructure
                    </h2>
                </div>
                <!-- Meta Info Grid -->
                <div class="text-[10px] grid grid-cols-2 divide-x divide-y divide-slate-300">
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">AUTHOR</span>
                        <span class="text-slate-700 font-bold uppercase">{{ $template->author ?? '-' }}</span>
                    </div>
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">TANGGAL</span>
                        <span class="text-slate-700 font-bold">{{ $template->created_date ? $template->created_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">STATUS</span>
                        <span class="text-slate-700 font-bold uppercase">{{ $template->status ?? 'AKTIF' }}</span>
                    </div>
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">REVISI</span>
                        <span class="text-slate-700 font-bold">{{ $template->revision ?? '01' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Note -->
        @if($template->description)
        <div class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
            {{ $template->description }}
        </div>
        @endif

        <!-- Validation Errors Summary -->
        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl space-y-1 shadow-sm">
            <span class="text-xs font-bold block uppercase tracking-wider">Terdapat beberapa kesalahan pengisian:</span>
            <ul class="list-disc pl-5 text-xs font-medium space-y-0.5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Section 1: Identitas (Statis) -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="w-6 h-6 rounded bg-blue-900 text-white flex items-center justify-center text-xs font-bold">1</span>
                Identitas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border border-slate-200 rounded-2xl bg-slate-50/20">

                <!-- Pemohon -->
                <div class="space-y-4">
                    <span class="text-xs font-bold text-blue-900 uppercase tracking-wider block bg-blue-50/50 px-3 py-1 rounded">Pemohon</span>

                    <div class="space-y-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama Pemohon <span class="text-red-500">*</span></label>
                            <input type="text" name="pemohon_nama" value="{{ old('pemohon_nama') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="pemohon_jabatan" value="{{ old('pemohon_jabatan') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Departemen <span class="text-red-500">*</span></label>
                            <input type="text" name="pemohon_departemen" value="{{ old('pemohon_departemen') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                            <input type="date" name="pemohon_tgl_pengajuan" value="{{ old('pemohon_tgl_pengajuan', date('Y-m-d')) }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all">
                        </div>
                    </div>
                </div>

                <!-- Peruntukan -->
                <div class="space-y-4">
                    <span class="text-xs font-bold text-orange-950 uppercase tracking-wider block bg-orange-50/50 px-3 py-1 rounded">Peruntukan</span>

                    <div class="space-y-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama Peruntukan <span class="text-red-500">*</span></label>
                            <input type="text" name="peruntukan_nama" value="{{ old('peruntukan_nama') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                                placeholder="Contoh: Widia">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="peruntukan_jabatan" value="{{ old('peruntukan_jabatan') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                                placeholder="Contoh: Staff">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Departemen <span class="text-red-500">*</span></label>
                            <input type="text" name="peruntukan_departemen" value="{{ old('peruntukan_departemen') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                                placeholder="Contoh: PPSTI">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">SLA Deadline</label>
                            <input type="text" name="peruntukan_sla_deadline" value="{{ old('peruntukan_sla_deadline') }}"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                                placeholder="Contoh: 3 Hari Kerja">
                            <p class="text-[10px] text-slate-400 mt-1 font-medium">*) SLA Deadline diisi berdasarkan kesepakatan pemohon dan tim IT</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Dynamic Sections & Fields -->
        @foreach($template->sections as $sec)
        <div class="space-y-5">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                {{ $sec->title }}
            </h3>

            <div class="space-y-5">
                @foreach($sec->fields as $field)
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        {{ $field->label }}
                        @if($field->is_required)
                        <span class="text-red-500 font-bold">*</span>
                        @endif
                    </label>

                    <!-- TEXT Input -->
                    @if($field->type === 'text')
                    <input type="text" name="fields[{{ $field->id }}]"
                        value="{{ old('fields.' . $field->id) }}"
                        @if($field->is_required) required @endif
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all">

                    <!-- TEXTAREA Input -->
                    @elseif($field->type === 'textarea')
                    <textarea name="fields[{{ $field->id }}]" rows="4"
                        @if($field->is_required) required @endif
                                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                                          placeholder="{{ isset($field->config['placeholder']) ? $field->config['placeholder'] : '' }}">{{ old('fields.' . $field->id) }}</textarea>

                    <!-- CHECKBOX Centang Tunggal -->
                    @elseif($field->type === 'checkbox')
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="fields[{{ $field->id }}]" value="1"
                            {{ old('fields.' . $field->id) ? 'checked' : '' }}
                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                        Centang untuk menyetujui / memilih
                    </label>

                    <!-- CHECKBOX GROUP Pilihan Ganda -->
                    @elseif($field->type === 'checkbox_group')
                    <div class="flex flex-wrap gap-6 pt-1">
                        @foreach($field->options as $opt)
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="fields[{{ $field->id }}][]" value="{{ $opt }}"
                                {{ is_array(old('fields.' . $field->id)) && in_array($opt, old('fields.' . $field->id)) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                            {{ $opt }}
                        </label>
                        @endforeach
                    </div>

                    <!-- TABLE Dinamis (Biaya, Waktu, Lainnya) -->
                    @elseif($field->type === 'table' && isset($field->config['columns']))
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-2.5 px-4 w-12 text-center">Centang</th>
                                    <th class="py-2.5 px-4 w-1/4">{{ $field->config['columns'][0] }}</th>
                                    <th class="py-2.5 px-4">{{ $field->config['columns'][1] }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($field->config['rows'] as $row)
                                @php
                                $oldChecked = old("fields.{$field->id}.{$row['id']}.checked");
                                $oldText = old("fields.{$field->id}.{$row['id']}.text");
                                @endphp
                                <tr class="hover:bg-slate-50/30">
                                    <td class="py-3 px-4 text-center">
                                        <input type="checkbox" name="fields[{{ $field->id }}][{{ $row['id'] }}][checked]" value="1"
                                            {{ $oldChecked ? 'checked' : '' }}
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-700">{{ $row['label'] }}</td>
                                    <td class="py-2 px-4">
                                        <input type="text" name="fields[{{ $field->id }}][{{ $row['id'] }}][text]" value="{{ $oldText }}"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                                            placeholder="Tuliskan penjelasan dampak di sini...">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold rounded-xl text-xs transition-colors">
                Batalkan
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl text-xs shadow-lg shadow-blue-900/10 hover:shadow-xl hover:shadow-blue-900/20 transition-all duration-200">
                Kirim Formulir Pengajuan
            </button>
        </div>
    </form>
</div>
@endsection