@extends('layouts.admin')

@section('page_title', 'Detail Pengajuan Change Request')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Pengajuan
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('form.pdf', $submission->submission_code) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/10 transition-all duration-150">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / PDF
            </a>
        </div>
    </div>

    <!-- Paper Form Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 sm:p-12 space-y-8">

        <!-- Document Header (Replicating Image) -->
        <div class="border border-slate-300 rounded-lg overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-300">
                <!-- Logo Dahana -->
                <div class="p-6 flex items-center justify-center bg-slate-50/50">
                    <img
                        src="{{ asset('images/logo-dahana.png') }}"
                        alt="PT Dahana"
                        class="h-24 w-auto">
                </div>
                <!-- Title Form -->
                <div class="md:col-span-2 p-6 flex flex-col justify-center text-center">
                    <h2 class="text-base font-extrabold text-slate-800 tracking-tight leading-snug">
                        {!! nl2br(e($submission->template->title)) !!}
                    </h2>
                </div>
                <!-- Meta Info Grid -->
                <div class="text-[10px] grid grid-cols-2 divide-x divide-y divide-slate-300">
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">AUTHOR</span>
                        <span class="text-slate-700 font-bold uppercase">{{ $submission->template->author ?? '-' }}</span>
                    </div>
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">TANGGAL</span>
                        <span class="text-slate-700 font-bold">{{ $submission->template->created_date ? $submission->template->created_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">STATUS</span>
                        <span class="text-slate-700 font-bold uppercase">{{ $submission->template->status ?? 'AKTIF' }}</span>
                    </div>
                    <div class="p-2">
                        <span class="text-slate-400 block font-semibold">REVISI</span>
                        <span class="text-slate-700 font-bold">{{ $submission->template->revision ?? '01' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Note -->
        @if($submission->template->description)
        <div class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
            {{ $submission->template->description }}
        </div>
        @endif

        <!-- Section 1: Identitas -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="w-6 h-6 rounded bg-slate-100 text-slate-700 flex items-center justify-center text-xs">1</span>
                Identitas
            </h3>

            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200">

                    <!-- Pemohon -->
                    <div class="p-5 space-y-3.5">
                        <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider block bg-blue-50/50 px-2.5 py-1 rounded">Pemohon</span>
                        <div class="grid grid-cols-3 text-xs gap-y-2">
                            <span class="text-slate-400 font-semibold">Nama:</span>
                            <span class="col-span-2 text-slate-800 font-semibold">{{ $submission->pemohon_nama }}</span>

                            <span class="text-slate-400 font-semibold">Jabatan:</span>
                            <span class="col-span-2 text-slate-800">{{ $submission->pemohon_jabatan }}</span>

                            <span class="text-slate-400 font-semibold">Departemen:</span>
                            <span class="col-span-2 text-slate-800">{{ $submission->pemohon_departemen }}</span>

                            <span class="text-slate-400 font-semibold">Tgl Pengajuan:</span>
                            <span class="col-span-2 text-slate-800 font-medium">{{ $submission->pemohon_tgl_pengajuan ? $submission->pemohon_tgl_pengajuan->format('d F Y') : '-' }}</span>
                        </div>
                    </div>

                    <!-- Peruntukan -->
                    <div class="p-5 space-y-3.5">
                        <span class="text-[11px] font-bold text-orange-950 uppercase tracking-wider block bg-orange-50/50 px-2.5 py-1 rounded">Peruntukan</span>
                        <div class="grid grid-cols-3 text-xs gap-y-2">
                            <span class="text-slate-400 font-semibold">Nama:</span>
                            <span class="col-span-2 text-slate-800 font-semibold">{{ $submission->peruntukan_nama }}</span>

                            <span class="text-slate-400 font-semibold">Jabatan:</span>
                            <span class="col-span-2 text-slate-800">{{ $submission->peruntukan_jabatan }}</span>

                            <span class="text-slate-400 font-semibold">Departemen:</span>
                            <span class="col-span-2 text-slate-800">{{ $submission->peruntukan_departemen }}</span>

                            <span class="text-slate-400 font-semibold">SLA Deadline:</span>
                            <span class="col-span-2 text-slate-800 font-bold text-blue-600">{{ $submission->peruntukan_sla_deadline ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Sections & Fields -->
        @php
        $approvalFields = [];
        @endphp

        @foreach($submission->template->sections as $sec)
        <!-- Skip approval rendering in main flow, we will render it beautifully at the bottom -->
        @if(str_contains(strtolower($sec->title), 'approval'))
        @php
        $approvalFields = $sec->fields;
        @endphp
        @continue
        @endif

        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                {{ $sec->title }}
            </h3>

            <div class="space-y-5">
                @foreach($sec->fields as $field)
                @php
                $val = $submission->getValueForField($field->id);
                @endphp

                <div class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $field->label }}</span>

                    <!-- TEXT / TEXTAREA Display -->
                    @if(in_array($field->type, ['text', 'textarea']))
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80 text-xs text-slate-700 font-medium whitespace-pre-line leading-relaxed min-h-[38px]">
                        {{ $val ?: '-' }}
                    </div>

                    <!-- CHECKBOX Display -->
                    @elseif($field->type === 'checkbox')
                    <div class="flex items-center gap-2 text-xs">
                        <span class="w-5 h-5 rounded border flex items-center justify-center {{ $val ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-100 border-slate-300' }}">
                            @if($val) ✓ @endif
                        </span>
                        <span class="font-semibold text-slate-700">{{ $val ? 'Centang' : 'Tidak dicentang' }}</span>
                    </div>

                    <!-- CHECKBOX GROUP Display -->
                    @elseif($field->type === 'checkbox_group')
                    <div class="flex flex-wrap gap-4">
                        @foreach($field->options as $opt)
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-5 h-5 rounded border flex items-center justify-center {{ is_array($val) && in_array($opt, $val) ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-100 border-slate-300' }}">
                                @if(is_array($val) && in_array($opt, $val)) ✓ @endif
                            </span>
                            <span class="font-semibold text-slate-700">{{ $opt }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- TABLE Display -->
                    @elseif($field->type === 'table' && isset($field->config['columns']))
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-2.5 px-4 w-12 text-center">Pilih</th>
                                    <th class="py-2.5 px-4 w-1/4">{{ $field->config['columns'][0] }}</th>
                                    <th class="py-2.5 px-4">{{ $field->config['columns'][1] }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($field->config['rows'] as $row)
                                @php
                                $rowVal = $val[$row['id']] ?? null;
                                $checked = !empty($rowVal['checked']);
                                $text = $rowVal['text'] ?? '';
                                @endphp
                                <tr class="hover:bg-slate-50/20">
                                    <td class="py-3 px-4 text-center">
                                        <span class="w-5 h-5 inline-flex rounded border items-center justify-center {{ $checked ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-100 border-slate-300' }}">
                                            @if($checked) ✓ @endif
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ $row['label'] }}</td>
                                    <td class="py-3 px-4 text-slate-600 font-medium">{{ $text ?: '-' }}</td>
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

        @inject('qrService', 'App\Services\QrCodeService')

        <!-- Render Signature Approval Section (Grid Replicating Image) -->
        @if(count($approvalFields) > 0)
        <div class="space-y-4 pt-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                4. Approval
            </h3>

            <!-- Replicating signatures in nice borders/grids -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">

                @php
                // Group approvals by 'group' config
                $groupedApprovals = [];
                foreach($approvalFields as $f) {
                    $grp = $f->config['group'] ?? 'Approval';
                    $groupedApprovals[$grp][] = $f;
                }
                $allApprovalFields = $approvalFields->sortBy('order')->values();
                @endphp

                <div class="divide-y divide-slate-200">
                    @foreach($groupedApprovals as $groupName => $fields)
                    <div>
                        <!-- Group Header -->
                        <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-center">
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">{{ $groupName }}</span>
                        </div>

                        <!-- Group Fields columns -->
                        <div class="grid grid-cols-1 {{ count($fields) > 1 ? 'md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200' : '' }}">
                            @foreach($fields as $fld)
                            @php
                            $fieldIndex = $allApprovalFields->search(fn($item) => $item->id === $fld->id);
                            $stepNumber = $fieldIndex !== false ? ($fieldIndex + 1) : null;
                            $approval = $stepNumber ? $submission->approvals->where('step', $stepNumber)->first() : null;
                            $isPemohonStep = ($fld->config['jenis_approval'] ?? '') === 'pemohon';
                            @endphp
                            <div class="p-6 flex flex-col justify-between items-center text-center min-h-[170px]">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">{{ $fld->label }}</span>

                                <!-- Status / QR Code area -->
                                <div class="py-2 flex items-center justify-center min-h-[90px]">
                                    @if($approval)
                                        @if(in_array($approval->status, ['approved', 'rejected', 'revision']))
                                            @if($isPemohonStep && $approval->status === 'approved')
                                                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                                    ✓ Diajukan
                                                </span>
                                            @else
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-lg p-1 shadow-xs flex items-center justify-center">
                                                        {!! $qrService->getQrSvgInline($approval) !!}
                                                    </div>
                                                    <a href="{{ $approval->verifyUrl() }}" target="_blank" class="text-[9px] text-blue-600 hover:underline font-semibold uppercase tracking-wider">
                                                        Verifikasi QR
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs font-semibold text-slate-400 block tracking-wide italic">
                                                Belum Disetujui
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs italic text-slate-300">Belum diatur</span>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    @if($approval && $approval->status !== 'pending')
                                        <span class="text-xs font-bold text-slate-800 block underline">{{ $approval->approver_name }}</span>
                                        <span class="text-[9px] text-slate-500 font-medium block">{{ $approval->approver_position }}</span>
                                        
                                        @if($approval->status === 'approved')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider mt-1">
                                                Approved
                                            </span>
                                            @if($approval->acted_at)
                                                <span class="text-[8px] text-slate-400 block font-normal mt-0.5">{{ $approval->acted_at->format('d M Y H:i') }}</span>
                                            @endif
                                        @elseif($approval->status === 'rejected')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider mt-1">
                                                Rejected
                                            </span>
                                            @if($approval->comment)
                                                <p class="text-[9px] text-red-600 max-w-[200px] leading-tight font-medium italic mt-1">
                                                    Alasan: "{{ $approval->comment }}"
                                                </p>
                                            @endif
                                        @elseif($approval->status === 'revision')
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider mt-1">
                                                Revision
                                            </span>
                                            @if($approval->comment)
                                                <p class="text-[9px] text-amber-700 max-w-[200px] leading-tight font-medium italic mt-1">
                                                    Catatan: "{{ $approval->comment }}"
                                                </p>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-xs font-bold text-slate-400 block underline">
                                            {{ $approval ? $approval->approver_name : ($fld->config['approver_name'] ?? '-') }}
                                        </span>
                                        @if($fld->config['approver_position'] ?? $fld->config['subtitle'] ?? null)
                                            <span class="text-[9px] text-slate-400 font-medium block">
                                                {{ $fld->config['approver_position'] ?? $fld->config['subtitle'] }}
                                            </span>
                                        @endif
                                        <span class="text-[9px] text-slate-400 block font-semibold mt-1">
                                            Belum Disetujui
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
        @endif

    </div>
</div>
@endsection