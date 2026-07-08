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
            <div class="mb-4">
                <label>Nama</label>
                <input
                    type="text"
                    name="name"
                    class="w-full border rounded-xl px-4 py-2"
                    required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    class="w-full border rounded-xl px-4 py-2"
                    required>
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-xl px-4 py-2"
                    required>
            </div>

            <div class="mb-6">
                <label>Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border rounded-xl px-4 py-2"
                    required>
            </div>

            <button
                class="w-full bg-blue-900 text-white rounded-xl py-3">
                Daftar
            </button>
        </form>

        <div class="text-center mt-5">Sudah punya akun?
            <a href="{{ route('login') }}"
                class="text-blue-700 font-semibold">Login</a>
        </div>
    </div>
</div>
@endsection