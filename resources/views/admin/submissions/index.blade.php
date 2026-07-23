@extends('layouts.admin')

@section('page_title', 'Data Pengajuan Change Request')

@section('content')
<div class="space-y-6">

    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800 title-font">Daftar Pengajuan Masuk</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data pengisian form change request oleh user</p>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="flex gap-2 items-center">
            <select name="template_id" onchange="this.form.submit()"
                class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="">Semua Template Form</option>
                @foreach($templates as $tpl)
                <option value="{{ $tpl->id }}" {{ request('template_id') == $tpl->id ? 'selected' : '' }}>
                    {{ $tpl->title }}
                </option>
                @endforeach
            </select>
            @if(request('template_id'))
            <a href="{{ route('admin.submissions.index') }}" class="text-[10px] bg-slate-100 hover:bg-slate-200 px-2 py-1.5 rounded-lg font-bold text-slate-600">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Submissions Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($submissions->isEmpty())
        <div class="p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h4 class="text-sm font-bold text-slate-700">Belum ada pengajuan</h4>
            <p class="text-xs text-slate-500 mt-1">Data pengisian formulir dari user akan muncul di sini jika ada.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Kode Pengajuan</th>
                        <th class="py-4 px-6">Nama Pemohon</th>
                        <th class="py-4 px-6">Departemen</th>
                        <th class="py-4 px-6">Tanggal Pengajuan</th>
                        <th class="py-4 px-6">Template Form</th>
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($submissions as $sub)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-semibold text-blue-900">{{ $sub->submission_code }}</td>
                        <td class="py-4 px-6 text-slate-700 font-medium">{{ $sub->pemohon_nama }}</td>
                        <td class="py-4 px-6 text-slate-500">{{ $sub->pemohon_departemen }}</td>
                        <td class="py-4 px-6 text-slate-500">{{ $sub->pemohon_tgl_pengajuan ? $sub->pemohon_tgl_pengajuan->format('d-m-Y') : '-' }}</td>
                        <td class="py-4 px-6">
                            <div class="max-w-[260px]">
                                <p class="text-sm font-semibold text-slate-700 leading-5 break-words">
                                    {{ $sub->template->title }}
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-400 text-xs">{{ $sub->created_at->format('d-m-Y H:i') }}</td>
                        <td class="py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                            <!-- Detail Button -->
                            <a href="{{ route('admin.submissions.show', $sub->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <!-- PDF Export Button -->
                            <button
                                type="button"
                                onclick="printPdf('{{ route('form.pdf', $sub->submission_code) }}')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition-colors"
                                title="Cetak PDF">

                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>

                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.submissions.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-rose-500 hover:bg-rose-50 text-rose-600 transition-colors" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
<script>
function printPdf(url) {

    let iframe = document.getElementById('print-frame');

    if (!iframe) {
        iframe = document.createElement('iframe');
        iframe.id = 'print-frame';
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
    }

    iframe.src = url;
}
</script>

@endsection