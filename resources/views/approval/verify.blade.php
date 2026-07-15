@extends('layouts.app')

@section('title', 'Verifikasi Dokumen Approval')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    @if(!$valid)
        <!-- Invalid QR State -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-12 text-center space-y-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-rose-50 rounded-full text-rose-600 border border-rose-100 shadow-sm animate-pulse">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="space-y-2">
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">
                    Verifikasi Gagal
                </h1>
                <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Tanda tangan QR Code tidak terdaftar dalam database kami atau telah kadaluarsa.
                </p>
            </div>
            <div class="pt-4 border-t border-slate-100">
                <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-xs font-semibold text-rose-800">
                    🔍 Approval tidak ditemukan.
                </div>
            </div>
            <div class="pt-4">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition-colors">
                    Kembali ke Portal
                </a>
            </div>
        </div>
    @else
        <!-- Valid QR State -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden animate-fade-in">
            <!-- Top Status Bar -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-8 py-6 text-white text-center space-y-2">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white/20 backdrop-blur-md rounded-full border border-white/30 mb-1">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-lg font-bold tracking-wide uppercase">
                    Persetujuan Terverifikasi
                </h1>
                <p class="text-[11px] text-emerald-100/90 leading-relaxed font-medium">
                    Dokumen & tanda tangan elektronik ini sah dan diakui oleh PT Dahana.
                </p>
            </div>

            <!-- Verification Metadata -->
            <div class="p-8 space-y-6">
                <!-- Info Section 1: Dokumen -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-1.5">
                        Dokumen Info
                    </h3>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Nomor Dokumen</div>
                        <div class="text-slate-800 font-bold col-span-2">: {{ $approval->submission->submission_code }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Judul Form</div>
                        <div class="text-slate-800 font-semibold col-span-2">: {{ $approval->submission->template->title }}</div>
                    </div>
                </div>

                <!-- Info Section 2: Approver -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-1.5">
                        Identitas Approver
                    </h3>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Nama Approver</div>
                        <div class="text-slate-800 font-bold col-span-2">: {{ $approval->approver_name }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">NIP</div>
                        <div class="text-slate-800 font-medium col-span-2">: {{ $approval->approverUser->nip ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Jabatan</div>
                        <div class="text-slate-800 font-semibold col-span-2">: {{ $approval->approver_position }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Departemen</div>
                        <div class="text-slate-800 font-medium col-span-2">: {{ $approval->approverUser->department ?? '-' }}</div>
                    </div>
                </div>

                <!-- Info Section 3: Status & Waktu -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-1.5">
                        Status & Waktu Approval
                    </h3>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Status Approval</div>
                        <div class="col-span-2">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                {{ strtoupper($approval->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Tanggal Approval</div>
                        <div class="text-slate-800 font-semibold col-span-2">: {{ $approval->acted_at ? $approval->acted_at->translatedFormat('d F Y') : '-' }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Jam Approval</div>
                        <div class="text-slate-800 font-semibold col-span-2">: {{ $approval->acted_at ? $approval->acted_at->format('H:i:s') . ' WIB' : '-' }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">Approval Step</div>
                        <div class="text-slate-800 font-bold col-span-2">: Langkah {{ $approval->step }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 py-1 text-xs">
                        <div class="text-slate-500 font-medium col-span-1">UUID Approval</div>
                        <div class="text-slate-500 font-mono text-[10px] break-all col-span-2">: {{ $approval->approval_uuid }}</div>
                    </div>
                </div>

                <!-- Footer / Back button -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-4">
                    <p class="text-[9px] text-slate-400 leading-normal max-w-xs">
                        * Data verifikasi ini diambil secara realtime dari database PT Dahana untuk memastikan validitas dokumen.
                    </p>
                    <a href="{{ route('home') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-xs transition-colors shadow-sm">
                        Kembali ke Portal
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
