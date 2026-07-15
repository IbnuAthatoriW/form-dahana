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

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-100">

                <tr class="text-left text-slate-700">

                    <th class="px-6 py-4 font-semibold">
                        Nomor / Perihal
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        Konseptor
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        Tanggal
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        Kepada
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        Status
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        Tanggal Update
                    </th>

                    <th class="px-6 py-4 font-semibold text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            @foreach($mySubmissions as $submission)

                <tr class="border-t hover:bg-slate-50">

                    <td class="px-6 py-5">

                        <div class="font-semibold text-blue-900">

                            {{ $submission->submission_code }}

                        </div>

                        <div class="text-slate-600 mt-1">

                            {{ $submission->template->title }}

                        </div>

                    </td>

                    <td class="px-6 py-5">

                        {{ $submission->user->name }}

                    </td>

                    <td class="px-6 py-5">

                        {{ $submission->created_at->format('d M Y') }}

                        <br>

                        <span class="text-xs text-slate-400">

                            {{ $submission->created_at->format('H:i') }}

                        </span>

                    </td>

                    <td class="px-6 py-5">

                    @php
                    $currentApproval = $submission->currentReceiver();
                    @endphp

                    @if($currentApproval)

                        <div class="font-semibold text-blue-900">

                            {{ $currentApproval->approver_name }}

                        </div>

                        <div class="text-xs text-slate-500">

                            {{ $currentApproval->approver_position }}

                        </div>

                    @else

                        <span class="text-green-600 font-semibold">

                            Selesai

                        </span>

                    @endif

                    </td>

                    @php
                        $badge = match($submission->workflow_status) {
                            'approved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            'revision' => 'bg-yellow-100 text-yellow-700',
                            default => 'bg-blue-100 text-blue-700',
                        };
                    @endphp

                    <td class="px-6 py-5">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                            {{ ucfirst($submission->workflow_status) }}
                        </span>
                    </td>

                    <td class="px-6 py-5">
                        @php
                            $latest = $submission->latestApproval();
                        @endphp
                        @if($latest && $latest->acted_at)
                            <div class="font-semibold text-slate-700">
                                {{ $latest->acted_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ $latest->acted_at->format('H:i') }}
                            </div>
                        @else
                            <div class="font-semibold text-slate-700">
                                {{ $submission->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ $submission->created_at->format('H:i') }}
                            </div>
                        @endif
                    </td>

                    <td class="px-6 py-5">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('form.pdf',$submission->submission_code) }}"
                               class="px-3 py-2 rounded-lg bg-blue-900 text-white text-xs hover:bg-blue-800 transition">
                                PDF
                            </a>
                            <button
                                onclick="showTimeline('{{ $submission->id }}')"
                                class="px-3 py-2 rounded-lg border border-slate-300 text-xs hover:bg-slate-100 hover:border-slate-400 transition">
                                Detail
                            </button>
                        </div>
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

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

<!-- Modal Detail Timeline -->
<div id="timelineModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeTimeline()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-5 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold title-font" id="modal-title">Detail Progres Approval</h3>
                    <p class="text-xs text-blue-200 mt-1" id="modal-subtitle">Loading...</p>
                </div>
                <button type="button" class="text-white/80 hover:text-white transition focus:outline-none" onclick="closeTimeline()">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="px-6 py-6 space-y-6">
                <!-- Submission Info Grid -->
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">Nomor Pengajuan</span>
                        <span class="font-bold text-slate-800" id="info-code">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">Konseptor / Pengaju</span>
                        <span class="font-bold text-slate-800" id="info-pemohon">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">Tanggal Dibuat</span>
                        <span class="font-bold text-slate-800" id="info-created">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5 font-medium">Status Saat Ini</span>
                        <span class="font-bold text-slate-800" id="info-status">-</span>
                    </div>
                </div>

                <!-- Stepper Area -->
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-4 font-semibold">Timeline Perjalanan Dokumen</h4>
                    <div id="stepper-content" class="space-y-6 relative before:absolute before:inset-y-2 before:left-4 before:w-0.5 before:bg-slate-200 before:content-['']">
                        <!-- Stepper items injected dynamically -->
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-50 px-6 py-4 flex justify-end rounded-b-3xl">
                <button type="button" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs transition" onclick="closeTimeline()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showTimeline(submissionId) {
    const modal = document.getElementById('timelineModal');
    const subtitle = document.getElementById('modal-subtitle');
    const infoCode = document.getElementById('info-code');
    const infoPemohon = document.getElementById('info-pemohon');
    const infoCreated = document.getElementById('info-created');
    const infoStatus = document.getElementById('info-status');
    const stepper = document.getElementById('stepper-content');

    // Reset content
    stepper.innerHTML = '<div class="text-slate-500 text-xs text-center py-4">Memuat data timeline...</div>';
    modal.classList.remove('hidden');

    fetch(`/submission/${submissionId}/timeline`)
        .then(response => response.json())
        .then(data => {
            subtitle.innerText = data.title;
            infoCode.innerText = data.submission_code;
            infoPemohon.innerText = data.pemohon_nama;
            infoCreated.innerText = data.created_at;
            infoStatus.innerText = data.status;

            stepper.innerHTML = '';
            if (data.approvals.length === 0) {
                stepper.innerHTML = '<div class="text-xs text-slate-500 text-center py-4">Tidak ada alur approval untuk dokumen ini.</div>';
                return;
            }

            data.approvals.forEach(app => {
                let badgeClass = 'bg-slate-100 text-slate-600 border border-slate-200';
                let circleClass = 'bg-slate-200 border-2 border-slate-300 text-slate-600';
                let statusText = app.status;

                if (app.status === 'approved') {
                    badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                    circleClass = 'bg-emerald-500 border-2 border-emerald-200 text-white shadow-md shadow-emerald-500/20';
                    statusText = 'Disetujui';
                } else if (app.status === 'rejected') {
                    badgeClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                    circleClass = 'bg-rose-500 border-2 border-rose-200 text-white shadow-md shadow-rose-500/20';
                    statusText = 'Ditolak';
                } else if (app.status === 'revision') {
                    badgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                    circleClass = 'bg-amber-500 border-2 border-amber-200 text-white shadow-md shadow-amber-500/20';
                    statusText = 'Perlu Revisi';
                } else {
                    statusText = 'Menunggu';
                }

                let commentHtml = '';
                if (app.comment) {
                    let commentBorderColor = 'border-slate-200 bg-slate-50';
                    if (app.status === 'rejected') commentBorderColor = 'border-rose-100 bg-rose-50/50';
                    if (app.status === 'revision') commentBorderColor = 'border-amber-100 bg-amber-50/50';

                    commentHtml = `
                        <div class="mt-2 text-[11px] p-2.5 rounded-lg border ${commentBorderColor} text-slate-600 italic">
                            <strong>Komentar:</strong> ${app.comment}
                        </div>
                    `;
                }

                const item = `
                    <div class="relative flex gap-4 items-start pl-8 group">
                        <!-- Bullet point -->
                        <div class="absolute left-0 top-0.5 w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs ${circleClass} z-10 transition-all duration-300">
                            ${app.step}
                        </div>

                        <!-- Card info -->
                        <div class="flex-1 bg-white p-4 rounded-2xl border border-slate-200 hover:border-blue-200 transition duration-200 shadow-xs">
                            <div class="flex justify-between items-start gap-2 flex-wrap">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800">${app.name}</h5>
                                    <p class="text-[10px] text-slate-500 font-medium">${app.position}</p>
                                </div>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-bold ${badgeClass}">
                                    ${statusText}
                                </span>
                            </div>

                            ${app.acted_at ? `
                                <div class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    ${app.acted_at}
                                </div>
                            ` : ''}

                            ${commentHtml}
                        </div>
                    </div>
                `;
                stepper.insertAdjacentHTML('beforeend', item);
            });
        })
        .catch(err => {
            console.error(err);
            stepper.innerHTML = '<div class="text-xs text-rose-500 text-center py-4">Gagal memuat data timeline. Silakan coba lagi.</div>';
        });
}

function closeTimeline() {
    document.getElementById('timelineModal').classList.add('hidden');
}
</script>
@endsection