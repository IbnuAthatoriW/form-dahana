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
                Lengkapi data diri Anda sebelum membuat Change Request.
            </p>
        </div>

        <form
            action="{{ route('profile.update') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="delete_photo" id="delete_photo" value="0">

            <div class="p-8">

                <div class="space-y-8">

                    <!-- FOTO -->
                                            <div class="flex flex-col items-center">

                            <label class="text-lg font-semibold text-slate-800 mb-4">
                                Foto Profil
                            </label>

                            <div class="w-56 h-56 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 bg-slate-100 shadow-sm">

                                @if($user->photo)
                                    <img
                                        id="preview-photo"
                                        src="{{ asset('storage/'.$user->photo) }}"
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
                                    class="cursor-pointer px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow transition">

                                    Pilih Foto
                                </label>

                                @if($user->photo)
                                <button
                                    type="button"
                                    id="remove-photo"
                                    class="px-5 py-2.5 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 transition">

                                    Hapus
                                </button>
                                @endif

                            </div>

                            <p class="text-xs text-slate-500 mt-2">
                                JPG, PNG • Maksimal 2 MB
                            </p>

                        </div>
                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    Nama Lengkap
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name',$user->name) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold mb-2">
                                    NIP
                                </label>

                                <input
                                    type="text"
                                    name="nip"
                                    value="{{ old('nip',$user->nip) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold mb-2">
                                    Jabatan
                                </label>

                                <input
                                    type="text"
                                    name="position"
                                    value="{{ old('position',$user->position) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold mb-2">
                                    Departemen
                                </label>

                                <input
                                    type="text"
                                    name="department"
                                    value="{{ old('department',$user->department) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold mb-2">
                                    No. HP
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone',$user->phone) }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">

                            </div>

                            <div class="md:col-span-2">

                                <label class="block text-sm font-semibold mb-2">
                                    Alamat
                                </label>

                                <textarea
                                    name="address"
                                    rows="4"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">{{ old('address',$user->address) }}</textarea>

                            </div>

                        </div>

                    </div>
                    <!--Signature-->
                    <div class="mt-10 border-t border-slate-200 pt-8">
                        <h2 class="text-xl font-bold text-slate-800">
                            Tanda Tangan Digital
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 mb-5">
                            Buat tanda tangan menggunakan mouse atau touchpad.
                        </p>
                        <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-4">
                            <canvas
                                id="signature-pad"
                                width="800"
                                height="250"
                                class="w-full bg-white rounded-xl border border-slate-300">
                            </canvas>
                        </div>
                        <input
                            type="hidden"
                            name="signature"
                            id="signature">
                        <div class="flex justify-between items-center mt-5">

                            <div class="flex gap-3">

                                <button
                                    type="button"
                                    id="clear-signature"
                                    class="px-5 py-2 rounded-xl bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition">
                                    Bersihkan TTD
                                </button>

                                <button
                                    type="button"
                                    id="reset-form"
                                    class="px-5 py-2 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 transition">
                                    Reset Form
                                </button>
                            </div>

                            <button
                                type="submit"
                                class="px-8 py-3 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-semibold transition">
                                Simpan Profil
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


</div>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
<script>
    const photo = document.getElementById('photo');
    const preview = document.getElementById('preview-photo');

    // Preview foto
    photo.addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);

            // kalau pilih foto baru, batalkan status hapus
            document.getElementById('delete_photo').value = "0";
        }
    });

    // Tombol hapus foto
    const removeBtn = document.getElementById('remove-photo');

    if (removeBtn) {
        removeBtn.addEventListener('click', function () {

            preview.src = "https://placehold.co/300x300?text=Foto";

            // Kosongkan input file
            photo.value = "";

            // Tandai untuk dihapus saat submit
            document.getElementById('delete_photo').value = "1";
        });
    }
    document.getElementById("reset-form").addEventListener("click", function () {

    if (!confirm("Yakin ingin mengosongkan semua isian?")) {
        return;
    }

    document.querySelector('input[name="name"]').value = "";
    document.querySelector('input[name="nip"]').value = "";
    document.querySelector('input[name="position"]').value = "";
    document.querySelector('input[name="department"]').value = "";
    document.querySelector('input[name="phone"]').value = "";
    document.querySelector('textarea[name="address"]').value = "";

    // Reset foto
    preview.src = "https://placehold.co/300x300?text=Foto";
    photo.value = "";
    document.getElementById("delete_photo").value = "1";

    // Reset tanda tangan
    signaturePad.clear();
    document.getElementById("signature").value = "";
});
</script>

<script>
    const canvas = document.getElementById("signature-pad");
    const signaturePad = new SignaturePad(canvas, {
        penColor: "black"
    });

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 250 * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);
    const savedSignature = @json($user->signature ? asset('storage/'.$user->signature) : null);
    if (savedSignature) {
        const image = new Image();
        image.onload = function() {
            signaturePad.clear();
            const ctx = canvas.getContext("2d");
            ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
        }
        image.src = savedSignature;
    }
    document.getElementById("clear-signature").onclick = function() {
        signaturePad.clear();
    }
    document.querySelector("form").addEventListener("submit", function() {
        if (!signaturePad.isEmpty()) {
            document.getElementById("signature").value = signaturePad.toDataURL("image/png");
        }
    });
</script>

<script>
    window.onload = function() {
        resizeCanvas();
    }
</script>
@endsection