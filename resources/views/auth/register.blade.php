@extends('layouts.app')

@section('title','Register')

@section('content')

<div class="flex justify-center items-center min-h-[60vh]">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <h2 class="text-2xl font-bold text-center mb-6">
            Register
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    Nama
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded-xl px-4 py-3"
                    required>

                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-xl px-4 py-3"
                    required>

                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    Password
                </label>

                <div class="relative">

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full border rounded-xl px-4 py-3 pr-12"
                        required>

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-blue-700">
                    </button>

                </div>

                @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6">
                <label class="block mb-2 font-semibold">
                    Konfirmasi Password
                </label>

                <div class="relative">

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="w-full border rounded-xl px-4 py-3 pr-12"
                        required>

                    <button
                        type="button"
                        id="togglePasswordConfirmation"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-blue-700">
                    </button>

                </div>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-900 hover:bg-blue-800 text-white rounded-xl py-3 font-semibold transition">
                Daftar
            </button>

        </form>

        <div class="text-center mt-5">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="text-blue-700 font-semibold hover:underline">
                Login
            </a>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const eyeOpen = `
    <svg xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.8"
        stroke="currentColor"
        class="w-5 h-5">
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322z"/>
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>`;

        const eyeSlash = `
    <svg xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.8"
        stroke="currentColor"
        class="w-5 h-5">
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M3 3l18 18"/>
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M10.73 5.08A10.94 10.94 0 0112 4.5c4.64 0 8.58 3.01 9.96 7.18a1.01 1.01 0 010 .64 10.96 10.96 0 01-3.13 4.53"/>
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M6.61 6.61A10.96 10.96 0 002.04 11.68a1.01 1.01 0 000 .64C3.42 16.49 7.36 19.5 12 19.5a10.9 10.9 0 005.39-1.39"/>
        <path stroke-linecap="round"
            stroke-linejoin="round"
            d="M9.88 9.88a3 3 0 104.24 4.24"/>
    </svg>`;

        function togglePassword(inputId, buttonId) {

            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);

            button.innerHTML = eyeOpen;

            button.addEventListener('click', function() {

                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerHTML = eyeSlash;
                } else {
                    input.type = 'password';
                    button.innerHTML = eyeOpen;
                }
            });
        }

        togglePassword('password', 'togglePassword');
        togglePassword('password_confirmation', 'togglePasswordConfirmation');
    });
</script>

@endsection