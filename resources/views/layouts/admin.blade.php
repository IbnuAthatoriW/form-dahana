<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | PT Dahana</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body{
            font-family:'Plus Jakarta Sans','Outfit',sans-serif;
        }

        .title-font{    
            font-family:'Outfit',sans-serif;
        }
    </style>
</head>

<body class="h-screen overflow-hidden bg-slate-100 text-slate-800">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-blue-900 text-slate-200 flex flex-col shadow-xl z-50 transition-transform duration-300 transform -translate-x-full lg:translate-x-0">

        <!-- Brand -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800 shrink-0">

            <img
                src="{{ asset('images/logo-dahana.png') }}"
                alt="PT Dahana"
                class="h-24 w-auto">

            <span class="text-[9px] uppercase tracking-widest bg-blue-800 text-blue-200 px-2 py-0.5 rounded-full ml-3 font-semibold">
                Admin
            </span>

        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                {{ request()->routeIs('admin.dashboard')
                ? 'bg-white/10 border-l-4 border-orange-400 text-white'
                : 'text-white hover:bg-white/5' }}">

                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                </svg>

                Dashboard

            </a>

            <!-- Template -->
            <a href="{{ route('admin.templates.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                {{ request()->routeIs('admin.templates.*')
                ? 'bg-white/10 border-l-4 border-orange-400 text-white'
                : 'text-white hover:bg-white/5' }}">

                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>

                Master Template Form

            </a>

            <!-- Submission -->
            <a href="{{ route('admin.submissions.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                {{ request()->routeIs('admin.submissions.*')
                ? 'bg-white/10 border-l-4 border-orange-400 text-white'
                : 'text-white hover:bg-white/5' }}">

                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>

                Data Pengajuan

            </a>

            <div class="pt-6 mt-6 border-t border-slate-800 space-y-1.5">

                <a href="{{ route('home') }}"
                    target="_blank"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white hover:bg-white/5 transition-all duration-200">

                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>

                    Lihat Portal Form

                </a>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">

                    @csrf

                    <button
                        class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                        text-red-400 hover:text-white hover:bg-red-500 transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>

                        Logout

                    </button>

                </form>

            </div>

        </nav>

        <!-- Footer -->
        <div class="mt-auto p-5 text-center text-[10px] text-slate-500 border-t border-slate-800">

            © {{ date('Y') }} PT Dahana STI

        </div>

    </aside>

    <!-- Mobile Drawer Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden"></div>

    <!-- CONTENT -->
    <div class="lg:ml-64 ml-0 flex-1 flex flex-col h-screen overflow-hidden bg-slate-100 text-slate-800">

        <!-- Navbar -->
        <header class="sticky top-0 z-40 h-16 bg-white border-b border-slate-200 shadow-sm flex items-center justify-between px-6 sm:px-8 shrink-0">

            <div class="flex items-center gap-3">
                <!-- Toggle Sidebar Button -->
                <button id="toggle-sidebar" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-slate-800 title-font">
                    @yield('page_title','Admin Dashboard')
                </h1>
            </div>

            <a href="{{ route('profile') }}"
                class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-2 hover:bg-slate-50">

                @if(auth()->user()->photo)

                    <img
                        src="{{ asset('storage/'.auth()->user()->photo) }}"
                        class="w-10 h-10 rounded-full object-cover">

                @else

                    <span class="text-xs text-slate-500 bg-blue-800 text-blue-200 px-2 py-0.5 rounded-full font-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>

                @endif

                <div>

                    <div class="text-xs text-slate-500">
                        Administrator
                    </div>

                    <div class="font-semibold text-slate-800">
                        {{ auth()->user()->name }}
                    </div>

                </div>

            </a>

        </header>

        <!-- Scroll Area -->
        <main class="flex-1 overflow-y-auto">

            <div class="max-w-7xl mx-auto p-4 sm:p-8">

                @if(session('success') && !session('success_login'))

                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700">
                        {{ session('success') }}
                    </div>

                @endif

                @if(session('error'))

                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700">
                        {{ session('error') }}
                    </div>

                @endif

                @yield('content')

            </div>

        </main>

    </div>

</div>

<!-- Scripts for popups and responsiveness -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mobile toggle sidebar
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('toggle-sidebar');

        if (toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // Login SweetAlert2 popup
        @if(session('success_login'))
            Swal.fire({
                title: 'Login Berhasil',
                text: 'Selamat datang kembali.',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                position: 'center'
            });
        @endif

        // Intercept logout forms for SweetAlert2 logout popup
        const logoutForms = document.querySelectorAll('.logout-form');
        logoutForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout Berhasil',
                    text: 'Sampai jumpa kembali.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    position: 'center'
                }).then(() => {
                    form.submit();
                });
            });
        });
    });
</script>

</body>
</html>