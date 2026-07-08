@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-100 shadow-xl p-8 relative overflow-hidden">
        
        <!-- Background accents -->
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl"></div>

        <div class="text-center mb-8 relative z-10">
            <h2 class="text-2xl font-bold text-slate-800 title-font">Log In Admin</h2>
            <p class="text-sm text-slate-500 mt-1">Masuk untuk mengelola Master Template Form</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5 relative z-10">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm" 
                           placeholder="admin@dahana.id">
                </div>
                @error('email')
                    <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" required
                           class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 text-sm" 
                           placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-500 hover:text-slate-700">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                    Ingat saya
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl shadow-lg shadow-blue-900/10 hover:shadow-xl hover:shadow-blue-900/20 transition-all duration-200 text-sm tracking-wide">
                Masuk
            </button>
        </form>

        <!-- Information Info Box -->
        <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100/50">
            <span class="block text-xs font-semibold text-blue-900 uppercase mb-1">Informasi Kredensial Default</span>
            <p class="text-[11px] text-blue-800/80 leading-relaxed">
                Gunakan kredensial berikut untuk masuk sebagai Admin:<br>
                <strong>Email:</strong> admin@dahana.id<br>
                <strong>Sandi:</strong> password
            </p>
        </div>
    </div>
</div>
@endsection
