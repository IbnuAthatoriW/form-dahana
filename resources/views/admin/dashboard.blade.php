@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stat Card: Templates -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Master Template Form</span>
                <span class="text-3xl font-extrabold text-slate-800 title-font block">{{ $templatesCount }}</span>
                <a href="{{ route('admin.templates.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1">
                    Kelola Template
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-blue-500/5 rounded-full blur-xl translate-x-4 translate-y-4"></div>
        </div>

        <!-- Stat Card: Submissions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Pengajuan User</span>
                <span class="text-3xl font-extrabold text-slate-800 title-font block">{{ $submissionsCount }}</span>
                <a href="{{ route('admin.submissions.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1">
                    Lihat Semua Pengajuan
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-orange-500/5 rounded-full blur-xl translate-x-4 translate-y-4"></div>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 title-font">Pengajuan Terakhir</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $recentSubmissions->count() }} Pengajuan formulir terbaru yang masuk dari user
                </p>
            </div>
            <a href="{{ route('admin.submissions.index') }}" class="text-xs font-semibold px-3.5 py-1.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                Lihat Semua
            </a>
        </div>

        @if($recentSubmissions->isEmpty())
        <div class="p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h4 class="text-sm font-bold text-slate-700">Belum ada pengajuan masuk</h4>
            <p class="text-xs text-slate-500 mt-1">Formulir yang diisi oleh user akan tampil di sini.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Kode Pengajuan</th>
                        <th class="py-4 px-6">Nama Pemohon</th>
                        <th class="py-4 px-6">Departemen</th>
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6">Template Form</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentSubmissions as $sub)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-semibold text-blue-900">{{ $sub->submission_code }}</td>
                        <td class="py-4 px-6 text-slate-700">{{ $sub->pemohon_nama }}</td>
                        <td class="py-4 px-6 text-slate-500">{{ $sub->pemohon_departemen }}</td>
                        <td class="py-4 px-6 text-slate-500">{{ $sub->created_at->format('d-m-Y H:i') }}</td>
                        <td class="py-4 px-6">
                            <span class="text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full font-medium">
                                {{ $sub->template->title }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-1.5">
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('form.pdf', $sub->submission_code) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition-colors" title="Cetak PDF">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection