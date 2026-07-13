@extends('layouts.app')

@section('title', 'Portal Pengajuan Change Request')

@section('content')
<div class="space-y-12 py-6">

    <!-- Hero Section -->
    <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-8 sm:p-12 shadow-xl border border-slate-800">
        <!-- Accent Glows -->
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-orange-600/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-2xl space-y-4">
            <span class="text-xs font-bold text-orange-500 uppercase tracking-widest block">Sistem Teknologi Informasi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight title-font text-white leading-tight">
                Portal Formulir Pengajuan Change Request
            </h1>
            <p class="text-sm text-slate-300 leading-relaxed max-w-xl">
                Silakan pilih formulir change request di bawah ini untuk mengajukan permohonan perubahan infrastruktur maupun aplikasi secara resmi di lingkungan PT Dahana.
            </p>
        </div>
    </div>

@auth
@if($mySubmissions->count())

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold text-slate-800 title-font">
                Perjalanan Pengajuan Saya
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Pantau seluruh progres formulir yang telah diajukan.
            </p>
        </div>

        <div class="hidden md:flex gap-3">

            <div class="px-4 py-2 rounded-xl bg-yellow-50 border border-yellow-200">
                <p class="text-[10px] uppercase text-yellow-600 font-bold">Waiting</p>
                <p class="text-lg font-bold text-yellow-700">
                    {{ $mySubmissions->where('status','submitted')->count() }}
                </p>
            </div>

            <div class="px-4 py-2 rounded-xl bg-green-50 border border-green-200">
                <p class="text-[10px] uppercase text-green-600 font-bold">Approved</p>
                <p class="text-lg font-bold text-green-700">
                    {{ $mySubmissions->where('status','approved')->count() }}
                </p>
            </div>

        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($mySubmissions as $submission)

        <div class="bg-white rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-lg transition-all duration-300 overflow-hidden">

            <div class="h-1.5 bg-blue-900"></div>

            <div class="p-6">

                <div class="flex justify-between items-start">

                    <div>

                        <p class="text-xs text-slate-400 font-semibold uppercase">
                            {{ $submission->submission_code }}
                        </p>

                        <h3 class="mt-2 font-bold text-slate-800 line-clamp-2">
                            {{ $submission->template->title }}
                        </h3>

                    </div>

                    @php

                        switch($submission->status){

                            case 'approved':
                                $color='bg-green-100 text-green-700';
                                break;

                            case 'rejected':
                                $color='bg-red-100 text-red-700';
                                break;

                            case 'revision':
                                $color='bg-yellow-100 text-yellow-700';
                                break;

                            default:
                                $color='bg-blue-100 text-blue-700';

                        }

                    @endphp

                    <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $color }}">
                        {{ ucfirst($submission->status) }}
                    </span>

                </div>

                <div class="mt-6">

                    <div class="flex justify-between text-xs mb-2">

                        <span class="text-slate-500">
                            Progress
                        </span>

                        <span class="font-semibold text-blue-900">
                            25%
                        </span>

                    </div>

                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">

                        <div class="bg-blue-900 h-full rounded-full"
                            style="width:25%">

                        </div>

                    </div>

                </div>

                <div class="mt-6 space-y-2 text-xs">

                    <div class="flex items-center gap-2 text-green-600">

                        ✔

                        <span>Form Submitted</span>

                    </div>

                    <div class="flex items-center gap-2 text-blue-700">

                        ●

                        <span>Menunggu Approval Manager</span>

                    </div>

                    <div class="flex items-center gap-2 text-slate-400">

                        ○

                        <span>STI</span>

                    </div>

                    <div class="flex items-center gap-2 text-slate-400">

                        ○

                        <span>Direktur</span>

                    </div>

                </div>

                <div class="mt-6 flex gap-2">

                    <a href="{{ route('form.pdf',$submission->submission_code) }}"
                        class="flex-1 py-2 rounded-xl bg-blue-900 text-white text-xs text-center font-semibold hover:bg-blue-800">

                        Preview PDF

                    </a>

                    <button
                        class="flex-1 py-2 rounded-xl border border-slate-300 text-xs font-semibold hover:bg-slate-50">

                        Detail

                    </button>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endif
@endauth

    <!-- Active Forms Section -->
    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800 title-font">Daftar Formulir Tersedia</h2>
            <p class="text-xs text-slate-500 mt-1">Pilih salah satu formulir aktif di bawah ini untuk mulai pengisian</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($templates->isEmpty())
            <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-sm font-bold text-slate-700">Belum ada formulir aktif</h3>
                <p class="text-xs text-slate-500 mt-1">Administrator sedang mempersiapkan template formulir pengajuan.</p>
            </div>
            @else
            @foreach($templates as $tpl)
            <div class="bg-white rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-lg transition-all duration-200 p-6 flex flex-col justify-between relative overflow-hidden group">

                <!-- Accent border -->
                <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-900 group-hover:bg-orange-500 transition-colors"></div>

                <div class="pl-4 space-y-4">
                    <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                        <span>Revisi: {{ $tpl->revision }}</span>
                        <span>Status: {{ $tpl->status }}</span>
                    </div>

                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-800 title-font group-hover:text-blue-900 transition-colors line-clamp-2">
                            {{ $tpl->title }}
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                            {{ $tpl->description }}
                        </p>
                    </div>

                    <div class="flex gap-4 items-center text-[11px] text-slate-500 pt-2">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $tpl->author }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $tpl->created_date ? $tpl->created_date->format('d-m-Y') : '-' }}
                        </span>
                    </div>
                </div>

                <div class="pl-4 pt-6 border-t border-slate-50 mt-6">
                    @guest
                    <a href="{{ route('login', ['redirect' => route('form.fill', $tpl->id)]) }}"
                        class="w-full inline-flex items-center justify-center py-2.5 bg-blue-900 group-hover:bg-blue-800 text-white font-semibold rounded-xl text-xs shadow-md shadow-blue-900/5 transition-all duration-150 gap-1.5">
                        @endguest

                        @auth
                        <a href="{{ route('form.fill', $tpl->id) }}"
                            class="w-full inline-flex items-center justify-center py-2.5 bg-blue-900 group-hover:bg-blue-800 text-white font-semibold rounded-xl text-xs shadow-md shadow-blue-900/5 transition-all duration-150 gap-1.5">
                            @endauth
                            Mulai Isi Formulir
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                </div>

            </div>
            @endforeach
            @endif
        </div>
    </div>

</div>
@endsection