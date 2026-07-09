@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="flex justify-center items-center min-h-[60vh]">

    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-100 shadow-xl p-8">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-800">
                Login
            </h2>

            <p class="text-sm text-slate-500 mt-2">
                Silakan login untuk melanjutkan
            </p>
        </div>

        <form action="{{ route('login') }}" method="POST">

            @csrf

            <!-- Email -->
            <div class="mb-5">
                <label class="block text-sm font-semibold mb-2">
                    Alamat Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full rounded-xl border border-slate-300 p-3"
                    required>
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label class="block text-sm font-semibold mb-2">
                    Kata Sandi
                </label>

                <div class="relative">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full rounded-xl border border-slate-300 p-3 pr-12"
                        required>

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-blue-700">

                        <!-- Eye Open -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24"
                            stroke-width="1.8" stroke="currentColor"
                            class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <!-- Eye Close -->
                        <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24"
                            stroke-width="1.8" stroke="currentColor"
                            class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.58 10.58A3 3 0 0013.42 13.42" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.88 5.09A9.77 9.77 0 0112 4.5c4.64 0 8.58 3.01 9.96 7.18a9.82 9.82 0 01-2.3 3.95M6.23 6.23A9.8 9.8 0 002.04 11.68a1.01 1.01 0 000 .64C3.42 16.49 7.36 19.5 12 19.5c1.61 0 3.13-.36 4.5-1" />
                        </svg>

                    </button>

                </div>
            </div>

            <div class="flex justify-between items-center mb-6">

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember">
                    Ingat saya
                </label>

                <a href="{{ route('password.request') }}"
                    class="text-sm text-blue-700 hover:underline">
                    Lupa Password?
                </a>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-900 hover:bg-blue-800 text-white rounded-xl py-3 font-semibold">
                Masuk
            </button>
        </form>

        <div class="text-center mt-6 text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-semibold text-blue-700 hover:underline">
                Daftar
            </a>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const password = document.getElementById('password');
        const toggle = document.getElementById('togglePassword');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClose = document.getElementById('eyeClose');

        toggle.addEventListener('click', function() {

            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClose.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClose.classList.add('hidden');
            }

        });

    });
</script>

@endsection