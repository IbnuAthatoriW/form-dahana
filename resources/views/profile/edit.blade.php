@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')

<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-8 py-6">
            <h1 class="text-2xl font-bold text-white">
                Profil Saya
            </h1>
            <p class="text-blue-100 mt-1">
                Lengkapi data diri Anda atau tinjau riwayat persetujuan dokumen Anda.
            </p>
        </div>

        <!-- Tab Switcher -->
        <div class="border-b border-slate-200 bg-slate-50/50 px-8 flex gap-6">
            <button type="button" id="tab-btn-profile" onclick="switchTab('profile')" class="py-4 text-xs font-bold uppercase tracking-wider text-blue-900 border-b-2 border-blue-900 transition-all focus:outline-none">
                👤 Detail Profil
            </button>
            <button type="button" id="tab-btn-history" onclick="switchTab('history')" class="py-4 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-700 transition-all focus:outline-none">
                📜 Riwayat Approval Saya
            </button>
        </div>

        <!-- Tab 1: Profile Form -->
        <div id="tab-pane-profile" class="block">
            <form
                id="profile-form"
                action="{{ route('profile.update') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="delete_photo" id="delete_photo" value="0">

                <div class="p-8">
                    <div class="space-y-8">
                        <!-- FOTO -->
                        <div class="flex flex-col items-center">
                            <label class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">
                                Foto Profil
                            </label>

                            <div class="w-48 h-48 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 bg-slate-100 shadow-sm">
                                @if($user->photo)
                                    <img
                                        id="preview-photo"
                                        src="{{ Storage::url($user->photo) }}?v={{ time() }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <img
                                        id="preview-photo"
                                        src="https://placehold.co/300x300?text=Foto"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>

                            <input
                                type="file"
                                name="photo"
                                id="photo"
                                class="hidden">

                            <div class="mt-5 flex items-center gap-3">
                                <label
                                    for="photo"
                                    class="cursor-pointer px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-semibold shadow-md transition text-xs">
                                    Pilih Foto
                                </label>

                                @if($user->photo)
                                <button
                                    type="button"
                                    id="remove-photo"
                                    class="px-5 py-2.5 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 font-semibold transition text-xs">
                                    Hapus
                                </button>
                                @endif
                            </div>

                            <p class="text-[10px] text-slate-500 mt-2">
                                JPG, PNG • Maksimal 2 MB
                            </p>
                        </div>

                        <!-- Data Fields -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Nama Lengkap
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        NIP / Nomor Induk Pegawai
                                    </label>
                                    <input
                                        type="text"
                                        name="nip"
                                        value="{{ old('nip', $user->nip) }}"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Jabatan
                                    </label>
                                    <input
                                        type="text"
                                        name="position"
                                        value="{{ old('position', $user->position) }}"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Departemen
                                    </label>
                                    <input
                                        type="text"
                                        name="department"
                                        value="{{ old('department', $user->department) }}"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        No. HP
                                    </label>
                                    <input
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone', $user->phone) }}"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
                                </div>

                                <div class="md:col-span-2 space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Alamat
                                    </label>
                                    <textarea
                                        name="address"
                                        rows="3"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex justify-end items-center gap-3 pt-6 border-t border-slate-100">
                            <button
                                type="button"
                                id="reset-form"
                                class="px-5 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold transition text-xs">
                                Reset Form
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-semibold transition text-xs shadow-lg shadow-blue-900/10 hover:shadow-xl">
                                Simpan Profil
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tab 2: Approval History -->
        <div id="tab-pane-history" class="hidden p-8">
            <div class="space-y-6">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                            Riwayat Persetujuan Dokumen
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Daftar seluruh pengajuan yang pernah Anda approve, reject, atau minta revisi.
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 font-bold text-[10px] rounded-full uppercase tracking-wider">
                        Total: {{ count($approvalHistory) }} Aksi
                    </span>
                </div>

                @if(count($approvalHistory) === 0)
                <div class="text-center py-12 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <div class="text-3xl">📭</div>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-3">Belum Ada Riwayat</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Anda belum pernah melakukan tindakan approval pada dokumen apapun.</p>
                </div>
                @else
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs bg-white">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3 px-4 text-center w-12">No</th>
                                    <th class="py-3 px-4">Nomor Dokumen</th>
                                    <th class="py-3 px-4">Judul Form</th>
                                    <th class="py-3 px-4">Sebagai</th>
                                    <th class="py-3 px-4 text-center w-28">Status</th>
                                    <th class="py-3 px-4 w-36">Tanggal Aksi</th>
                                    <th class="py-3 px-4 text-center w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($approvalHistory as $idx => $hist)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="py-4 px-4 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                                    <td class="py-4 px-4 font-bold text-slate-800">
                                        {{ $hist->submission->submission_code }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 font-medium">
                                        {{ $hist->submission->template->title }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ $hist->approver_position }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($hist->status === 'approved')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                                Disetujui
                                            </span>
                                        @elseif($hist->status === 'rejected')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider">
                                                Ditolak
                                            </span>
                                        @elseif($hist->status === 'revision')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-50 text-slate-700 border border-slate-200 uppercase tracking-wider">
                                                {{ $hist->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-slate-500 font-medium">
                                        @if($hist->acted_at)
                                            {{ $hist->acted_at->format('d M Y') }}
                                            <span class="block text-[10px] text-slate-400 font-normal mt-0.5">
                                                {{ $hist->acted_at->format('H:i') }} WIB
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('form.pdf', $hist->submission->submission_code) }}" target="_blank"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-800 font-semibold rounded-lg text-[10px] transition-colors border border-blue-100">
                                                📄 PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching logic
    function switchTab(tabId) {
        const btnProfile = document.getElementById('tab-btn-profile');
        const btnHistory = document.getElementById('tab-btn-history');
        const paneProfile = document.getElementById('tab-pane-profile');
        const paneHistory = document.getElementById('tab-pane-history');

        if (tabId === 'profile') {
            btnProfile.className = "py-4 text-xs font-bold uppercase tracking-wider text-blue-900 border-b-2 border-blue-900 transition-all focus:outline-none";
            btnHistory.className = "py-4 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-700 transition-all focus:outline-none";
            paneProfile.classList.remove('hidden');
            paneProfile.classList.add('block');
            paneHistory.classList.add('hidden');
            paneHistory.classList.remove('block');
        } else {
            btnHistory.className = "py-4 text-xs font-bold uppercase tracking-wider text-blue-900 border-b-2 border-blue-900 transition-all focus:outline-none";
            btnProfile.className = "py-4 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-700 transition-all focus:outline-none";
            paneHistory.classList.remove('hidden');
            paneHistory.classList.add('block');
            paneProfile.classList.add('hidden');
            paneProfile.classList.remove('block');
        }
    }

    // Auto switch tab if requested via URL
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'history') {
            switchTab('history');
        }
    });

    // Photo preview and removal logic
    const photo = document.getElementById('photo');
    const preview = document.getElementById('preview-photo');
    const deletePhoto = document.getElementById('delete_photo');

    let previewUrl = null;

    photo.addEventListener('change', function(e){
        const file = e.target.files[0];
        if(!file) return;

        if(previewUrl){
            URL.revokeObjectURL(previewUrl);
        }

        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        deletePhoto.value = "0";
    });

    const removeBtn = document.getElementById("remove-photo");
    if (removeBtn) {
        removeBtn.onclick = function () {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }
            preview.src = "https://placehold.co/300x300?text=Foto";
            photo.value = "";
            deletePhoto.value = "1";
        };
    }

    // Reset Form logic
    document.getElementById("reset-form").addEventListener("click", function () {
        if (!confirm("Yakin ingin menghapus foto dan mengosongkan data?")) {
            return;
        }

        document.querySelector('input[name="name"]').value = "";
        document.querySelector('input[name="nip"]').value = "";
        document.querySelector('input[name="position"]').value = "";
        document.querySelector('input[name="department"]').value = "";
        document.querySelector('input[name="phone"]').value = "";
        document.querySelector('textarea[name="address"]').value = "";

        preview.src = "https://placehold.co/300x300?text=Foto";
        photo.value = "";
        document.getElementById("delete_photo").value = "1";

        document.getElementById("profile-form").submit();
    });
</script>
@endsection