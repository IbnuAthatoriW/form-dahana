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

            <div class="mb-5">
                <label class="block text-sm font-semibold mb-2">
                    Kata Sandi
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full rounded-xl border border-slate-300 p-3"
                    required>
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

@endsection