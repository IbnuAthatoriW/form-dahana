<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Change Request Form') | PT Dahana</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
        }

        .title-font {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen text-slate-800 bg-slate-50 flex flex-col overflow-x-hidden">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @guest
        <!-- Header Navigation (Guest only) -->
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/logo-dahana.png') }}" alt="PT Dahana" class="h-24 w-auto">
                            <span class="hidden sm:inline-block h-6 w-[1px] bg-slate-200 mx-2"></span>
                            <span class="hidden sm:inline-block text-xs font-semibold text-slate-500 uppercase tracking-widest">Portal Form</span>
                        </a>
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2.5 rounded-full bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold shadow-md transition">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content (Guest only) -->
        <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            @if(session('success') && !session('success_login'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-xs animate-fade-in text-xs font-semibold">
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 shadow-xs animate-fade-in text-xs font-semibold">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>{{ session('error') }}</div>
            </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer (Guest only) -->
        <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 py-8 shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs">
                <p>&copy; {{ date('Y') }} PT Dahana (Persero) - Sistem Teknologi Informasi. All Rights Reserved.</p>
                <p class="mt-2 text-slate-600">Dikembangkan untuk keperluan kelola dokumen change request infrastruktur & aplikasi.</p>
            </div>
        </footer>
    @else
        <!-- Sidebar Layout (Authenticated User) -->
        <div class="flex h-screen w-full overflow-x-hidden bg-slate-100">
            <!-- SIDEBAR -->
            <aside id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-blue-900 text-slate-200 flex flex-col shadow-xl z-50 transition-transform duration-300 transform -translate-x-full lg:translate-x-0">
                <!-- Brand / Logo -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 shrink-0">
                    <img src="{{ asset('images/logo-dahana.png') }}" alt="PT Dahana" class="h-24 w-auto">
                    <span class="text-[9px] uppercase tracking-widest bg-blue-800 text-blue-200 px-2 py-0.5 rounded-full ml-3 font-semibold">
                        Portal
                    </span>
                </div>
                
                <!-- Menu -->
                <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5">
                    <!-- Dashboard -->
                    <a href="{{ route('home') }}"
                        class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('home') && !request()->has('section')? 'bg-white/10 border-l-4 border-orange-400 text-white': 'text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <!-- Pengajua Form -->
                    <a href="{{ route('home') }}?section=forms" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('form.fill') || (request()->routeIs('home') && request()->get('section') === 'forms') ? 'bg-white/10 border-l-4 border-orange-400 text-white' : 'text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Pengajuan Form
                    </a>
                    
                    <!-- Perjalanan Pengajuan -->
                    <a href="{{ route('home') }}?section=submissions" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') && request()->get('section') === 'submissions' ? 'bg-white/10 border-l-4 border-orange-400 text-white' : 'text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        Perjalanan Pengajuan
                    </a>
                    
                    <!-- Riwayat Approval -->
                    <a href="{{ route('approval.history') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                        {{ request()->routeIs('approval.history')
                            ? 'bg-white/10 border-l-4 border-orange-400 text-white'
                            : 'text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Riwayat Approval
                    </a>
                    
                    <!-- Separator & Logout -->
                    <div class="pt-6 mt-6 border-t border-slate-800 space-y-1.5">
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white hover:bg-white/5 transition-all duration-200">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Dashboard Admin
                        </a>
                        @endif
                        
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-400 hover:text-white hover:bg-red-500 transition-all duration-200">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
                <header class="sticky top-0 z-40 h-16 bg-white border-b border-slate-200 shadow-sm flex items-center justify-between px-8 shrink-0">
                    <div class="flex items-center gap-3">
                        <!-- Toggle Sidebar Button -->
                        <button id="toggle-sidebar" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-sm sm:text-base font-bold text-slate-800 title-font">
                            @yield('title', 'Change Request Form')
                        </h1>
                    </div>
                    
                    <a href="{{ route('profile') }}" class="profile-card topbar flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-2 hover:bg-slate-50">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="hidden sm:block text-left">
                            <div class="text-[10px] text-slate-400">User</div>
                            <div class="font-semibold text-xs text-slate-700 leading-none mt-0.5">{{ auth()->user()->name }}</div>
                        </div>
                    </a>
                </header>
                
                <!-- Scroll Area -->
                <div class="flex-grow {{ request()->routeIs('home') && !request()->has('section') ? 'overflow-hidden' : 'overflow-y-auto overflow-x-hidden' }}">
                    <div class="max-w-7xl mx-auto p-4 sm:p-8">
                        @if(session('success') && !session('success_login'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-xs animate-fade-in text-xs font-semibold">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>{{ session('success') }}</div>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 shadow-xs animate-fade-in text-xs font-semibold">
                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>{{ session('error') }}</div>
                        </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    @endguest

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