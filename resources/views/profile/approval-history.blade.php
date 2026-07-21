@extends('layouts.app')

@section('title', 'Riwayat Approval')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-8 py-6">
            <h1 class="text-2xl font-bold text-white">
                Riwayat Approval
            </h1>

            <p class="text-blue-100 mt-1">
                Daftar seluruh approval yang pernah Anda lakukan.
            </p>
        </div>

        <div class="p-8">

            @if($approvalHistory->isEmpty())

                <div class="text-center py-12">

                    <div class="text-5xl mb-4">
                        📭
                    </div>

                    <h3 class="text-lg font-semibold text-slate-700">
                        Belum ada riwayat approval
                    </h3>

                    <p class="text-slate-500 mt-2">
                        Anda belum pernah melakukan approval terhadap dokumen.
                    </p>

                </div>

            @else

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-slate-100">

                        <tr>

                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nomor Dokumen</th>
                            <th class="px-4 py-3 text-left">Judul Form</th>
                            <th class="px-4 py-3 text-left">Sebagai</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($approvalHistory as $index => $item)

                        <tr class="border-b hover:bg-slate-50">

                            <td class="px-4 py-3">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                {{ $item->submission->submission_code }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->submission->template->title }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->approver_position }}
                            </td>

                            <td class="px-4 py-3">

                            @if($item->submission->workflow_status == 'approved')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Disetujui
                                </span>

                            @elseif($item->submission->workflow_status == 'rejected')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    Ditolak
                                </span>

                            @elseif($item->submission->workflow_status == 'revision')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                    Perlu Revisi
                                </span>

                            @elseif($item->submission->workflow_status == 'waiting')
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    Menunggu Approval
                                </span>

                            @endif

                        </td>

                            <td class="px-4 py-3">
                                {{ optional($item->acted_at)->format('d M Y H:i') }}
                            </td>

                            <td class="px-4 py-3 text-center">

                                <a
                                    href="{{ route('form.pdf',$item->submission->submission_code) }}"
                                    target="_blank"
                                    class="inline-flex px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs">

                                    PDF

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

</div>

@endsection