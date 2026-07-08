@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-100 shadow-xl p-8 relative overflow-hidden">

        <!-- Background -->
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl"></div>

        <div class="text-center mb-8 relative z-10">
            <h2 class="text-2xl font-bold text-slate-800 title-font">
                Lupa Password
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Masukkan email dan password baru Anda.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5 relative z-10">

            @csrf

            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Alamat Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                @error('email')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                @error('password')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            </div>

            <!-- Tombol -->
            <button
                type="submit"
                class="w-full py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl shadow-lg transition">

                Simpan Password
            </button>

        </form>

        <!-- Kembali -->
        <div class="mt-6 text-center">

            <a href="{{ route('login') }}"
                class="text-sm text-blue-900 hover:text-blue-700 font-medium">

                ← Kembali ke Login

            </a>

        </div>

    </div>
</div>
@endsection