@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-100 shadow-xl p-8 relative overflow-hidden">

        <!-- Background -->
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl"></div>

        <div class="text-center mb-8 relative z-10">
            <h2 class="text-2xl font-bold text-slate-800">
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

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-blue-700">

                        <svg id="eyePassword" xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="w-5 h-5">

                            <path id="eyePath1"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322z" />

                            <path id="eyePath2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                    </button>
                </div>

                @error('password')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Konfirmasi Password
                </label>

                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                    <button
                        type="button"
                        id="togglePasswordConfirmation"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-blue-700">

                        <svg id="eyeConfirmation" xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="w-5 h-5">

                            <path id="confirmEyePath1"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322z" />

                            <path id="confirmEyePath2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                    </button>
                </div>
            </div>

            <!-- Tombol -->
            <button
                type="submit"
                class="w-full py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl shadow-lg transition">
                Simpan Password
            </button>

        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="text-sm text-blue-900 hover:text-blue-700 font-medium">
                ← Kembali ke Login
            </a>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function togglePassword(inputId, buttonId, path1Id, path2Id) {

            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const path1 = document.getElementById(path1Id);
            const path2 = document.getElementById(path2Id);

            button.addEventListener('click', function() {

                if (input.type === 'password') {

                    input.type = 'text';

                    path1.setAttribute(
                        'd',
                        'M3 3l18 18M10.477 10.485A3 3 0 0013.5 13.5M9.88 5.09A9.77 9.77 0 0112 4.5c4.64 0 8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639 11.05 11.05 0 01-2.373 4.04M6.228 6.228A11.042 11.042 0 002.036 11.683a1.012 1.012 0 000 .639C3.423 16.49 7.36 19.5 12 19.5a10.97 10.97 0 005.772-1.607'
                    );

                    path2.setAttribute('d', '');

                } else {

                    input.type = 'password';

                    path1.setAttribute(
                        'd',
                        'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322z'
                    );
                    path2.setAttribute(
                        'd',
                        'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                    );
                }
            });
        }

        togglePassword(
            'password',
            'togglePassword',
            'eyePath1',
            'eyePath2'
        );

        togglePassword(
            'password_confirmation',
            'togglePasswordConfirmation',
            'confirmEyePath1',
            'confirmEyePath2'
        );
    });
</script>

@endsection