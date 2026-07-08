@extends('layouts.app')

@section('title', 'Pengajuan Terkirim')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] py-12">
    <div class="w-full max-w-lg bg-white rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-12 text-center relative overflow-hidden">
        
        <!-- Background accents -->
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl"></div>

        <!-- Success Animation/Icon -->
        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md border border-emerald-100 animate-bounce">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-slate-800 title-font tracking-tight mb-2">Pengajuan Change Request Berhasil!</h2>
        <p class="text-sm text-slate-500 leading-relaxed mb-8">
            Formulir pengajuan Anda telah diterima oleh sistem dan sedang diproses oleh Tim IT. Simpan kode pengajuan Anda di bawah ini:
        </p>

        <!-- Submission Code Box -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 mb-8 relative group max-w-xs mx-auto">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Kode Pengajuan</span>
            <span class="text-2xl font-extrabold text-blue-900 title-font tracking-wider select-all">
                {{ $submission->submission_code }}
            </span>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 items-center justify-center">
            <!-- PDF Download -->
            <a href="{{ route('form.pdf', $submission->submission_code) }}" target="_blank"
               class="w-full sm:w-auto px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-rose-600/15 hover:shadow-lg hover:shadow-rose-600/20 transition-all duration-150 flex items-center justify-center gap-2">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Download PDF
            </a>

            <!-- Home link -->
            <a href="{{ route('home') }}"
               class="w-full sm:w-auto px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 font-semibold rounded-xl text-xs transition-colors flex items-center justify-center">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
