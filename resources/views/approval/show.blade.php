@extends('layouts.app')

@section('title', 'Persetujuan Dokumen - ' . $submission->submission_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header back link -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors mb-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Portal
    </a>

    <!-- Main Submission Details Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-12 space-y-8">
        
        <!-- Logo and Form Header Table -->
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
                    <span class="text-[10px] text-slate-400 font-bold tracking-wider block mb-1 uppercase">DOKUMEN PENGAJUAN</span>
                    <h2 class="text-base font-extrabold text-slate-800 tracking-tight leading-snug">
                        {{ $submission->template->title }}
                    </h2>
                    <span class="text-xs text-blue-900 font-bold mt-2 font-mono tracking-wider">{{ $submission->submission_code }}</span>
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
        <div class="text-xs text-slate-500 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 italic">
            {{ $submission->template->description }}
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
                <div class="space-y-3">
                    <span class="text-xs font-bold text-blue-900 uppercase tracking-wider block bg-blue-50/50 px-3 py-1 rounded">Pemohon</span>
                    <div class="divide-y divide-slate-100 text-xs">
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Nama</span> <span class="font-bold text-slate-800">{{ $submission->pemohon_nama }}</span></div>
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Jabatan</span> <span class="text-slate-700 font-medium">{{ $submission->pemohon_jabatan }}</span></div>
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Departemen</span> <span class="text-slate-700 font-medium">{{ $submission->pemohon_departemen }}</span></div>
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Tgl Pengajuan</span> <span class="text-slate-700 font-medium">{{ $submission->pemohon_tgl_pengajuan ? $submission->pemohon_tgl_pengajuan->format('d M Y') : '-' }}</span></div>
                    </div>
                </div>

                <!-- Peruntukan -->
                <div class="space-y-3">
                    <span class="text-xs font-bold text-orange-950 uppercase tracking-wider block bg-orange-50/50 px-3 py-1 rounded">Peruntukan</span>
                    <div class="divide-y divide-slate-100 text-xs">
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Nama</span> <span class="font-bold text-slate-800">{{ $submission->peruntukan_nama }}</span></div>
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Jabatan</span> <span class="text-slate-700 font-medium">{{ $submission->peruntukan_jabatan }}</span></div>
                        <div class="py-2 flex justify-between"><span class="text-slate-400">Departemen</span> <span class="text-slate-700 font-medium">{{ $submission->peruntukan_departemen }}</span></div>
                        <div class="py-2 flex justify-between"><span class="text-slate-400">SLA Deadline</span> <span class="font-bold text-slate-800">{{ $submission->peruntukan_sla_deadline ?? '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Sections & Fields -->
        @foreach($submission->template->sections as $sec)
        <div class="space-y-5">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                {{ $sec->title }}
            </h3>

            <div class="space-y-5">
                @foreach($sec->fields as $field)
                @php
                    $val = $submission->getValueForField($field->id);
                @endphp
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        {{ $field->label }}
                    </label>

                    <!-- TEXT / TEXTAREA Output -->
                    @if(in_array($field->type, ['text', 'textarea']))
                    <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 whitespace-pre-wrap leading-relaxed shadow-inner">
                        {{ $val ?: '-' }}
                    </div>

                    <!-- CHECKBOX Centang Tunggal -->
                    @elseif($field->type === 'checkbox')
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                        <span class="w-5 h-5 rounded border border-slate-300 flex items-center justify-center bg-slate-50 text-slate-600 font-bold">
                            {{ $val ? '✓' : '' }}
                        </span>
                        <span>{{ $field->label }}</span>
                    </div>

                    <!-- CHECKBOX GROUP Pilihan Ganda -->
                    @elseif($field->type === 'checkbox_group')
                    <div class="flex flex-wrap gap-4 pt-1">
                        @foreach($field->options as $opt)
                        @php
                            $checked = is_array($val) && in_array($opt, $val);
                        @endphp
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-slate-50/50 px-3 py-1.5 rounded-lg border border-slate-200">
                            <span class="w-4 h-4 rounded border border-slate-300 flex items-center justify-center bg-white text-blue-600 font-bold text-[10px]">
                                {{ $checked ? '✓' : '' }}
                            </span>
                            <span>{{ $opt }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- TABLE Dinamis (Biaya, Waktu, Lainnya) -->
                    @elseif($field->type === 'table' && isset($field->config['columns']))
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
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
                                <tr class="hover:bg-slate-50/30">
                                    <td class="py-3 px-4 text-center">
                                        <span class="w-4 h-4 rounded border border-slate-300 flex items-center justify-center bg-slate-50 text-blue-600 font-bold text-[10px] mx-auto">
                                            {{ $checked ? '✓' : '' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-700">{{ $row['label'] }}</td>
                                    <td class="py-2 px-4 text-slate-600">{{ $text ?: '-' }}</td>
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

        <!-- Section List Approval Current State -->
        @if($submission->approvals->count())
        <div class="space-y-4 pt-6 border-t border-slate-200">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                Status Approval Workflow
            </h3>
            
            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Step</th>
                            <th class="py-3 px-4">Approver</th>
                            <th class="py-3 px-4">Jabatan</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($submission->approvals as $approval)
                        @php
                            $badge = match($approval->status) {
                                'approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                'rejected' => 'bg-rose-50 text-rose-700 border border-rose-100',
                                'revision' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                default => 'bg-slate-50 text-slate-500 border border-slate-200',
                            };
                        @endphp
                        <tr>
                            <td class="py-3.5 px-4 font-semibold text-slate-500">Step {{ $approval->step }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $approval->approver_name }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $approval->approver_position }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $badge }}">
                                    {{ $approval->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 font-medium">
                                {{ $approval->acted_at ? $approval->acted_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">
                                {{ $approval->comment ?: '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    <!-- Active Approver Action Form Panel -->
    @php
        $currentApproval = $submission->currentReceiver();
        $isCurrentApprover = $currentApproval && auth()->check() && (auth()->user()->email == $currentApproval->approver_email);
    @endphp

    @if($isCurrentApprover)
    <form id="approval-action-form" method="POST" class="bg-white rounded-3xl border-2 border-blue-900 shadow-xl overflow-hidden p-8 sm:p-12 space-y-6">
        @csrf
        
        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
            <span class="w-3.5 h-3.5 rounded-full bg-orange-500 animate-pulse"></span>
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                    Persetujuan Dokumen Yang Ditujukan Kepada Anda
                </h3>
                <p class="text-[10px] text-slate-400 mt-0.5">
                    Silakan tinjau data di atas sebelum memberikan persetujuan, permintaan revisi, atau penolakan.
                </p>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                Komentar / Alasan Tindakan <span class="text-slate-400 font-normal lowercase">(Wajib diisi jika Anda menolak atau meminta revisi)</span>
            </label>
            <textarea id="comment-textarea" name="comment" rows="4" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs transition-all"
                placeholder="Tuliskan alasan penolakan, instruksi perbaikan revisi, atau catatan persetujuan Anda di sini..."></textarea>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <!-- Reject Button -->
            <button type="submit" formaction="{{ route('approval.reject', $submission->id) }}" onclick="return validateComment('reject')"
                class="px-5 py-3 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-rose-600/10 hover:shadow-lg transition-all duration-200">
                🚫 Tolak Dokumen
            </button>

            <!-- Revision Button -->
            <button type="submit" formaction="{{ route('approval.revision', $submission->id) }}" onclick="return validateComment('revision')"
                class="px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl text-xs shadow-md shadow-amber-600/10 hover:shadow-lg transition-all duration-200">
                ✏️ Minta Revisi
            </button>

            <!-- Approve Button -->
            <button type="submit" formaction="{{ route('approval.approve', $submission->id) }}" onclick="return confirmApprove()"
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs shadow-lg shadow-emerald-600/10 hover:shadow-xl transition-all duration-200">
                ✓ Setujui Dokumen
            </button>
        </div>
    </form>
    @else
    <!-- Informational box if not current approver -->
    <div class="bg-slate-100/50 rounded-2xl p-6 border border-slate-200 text-center space-y-2">
        <svg class="w-8 h-8 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Halaman Tinjau Dokumen</div>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
            @if($currentApproval)
                Dokumen ini saat ini sedang menunggu tindakan dari <strong>{{ $currentApproval->approver_name }}</strong> ({{ $currentApproval->approver_position }}). Anda tidak dapat melakukan aksi persetujuan.
            @else
                Proses alur approval untuk dokumen ini telah selesai.
            @endif
        </p>
    </div>
    @endif

</div>

<script>
function validateComment(action) {
    const comment = document.getElementById('comment-textarea').value.trim();
    if (!comment) {
        alert('Komentar / alasan wajib diisi untuk tindakan Tolak atau Minta Revisi!');
        return false;
    }
    const label = action === 'reject' ? 'Menolak' : 'Meminta Revisi';
    return confirm(`Apakah Anda yakin ingin ${label} dokumen ini?`);
}

function confirmApprove() {
    return confirm('Apakah Anda yakin ingin menyetujui dokumen ini? Tanda tangan profil Anda akan disematkan secara otomatis.');
}
</script>
@endsection
