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

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
        }

        .title-font {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-slate-200 flex flex-col shrink-0">
        <!-- Brand -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <img
                src="{{ asset('images/logo-dahana.png') }}"
                alt="PT Dahana"
                class="h-24 w-auto">
            <span class="text-[9px] uppercase tracking-widest bg-blue-900 text-blue-200 px-2 py-0.5 rounded-full ml-3 font-semibold">Admin</span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow py-6 px-4 space-y-1.5">
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                Dashboard
            </a>

            <!-- Templates CRUD Link -->
            <a href="{{ route('admin.templates.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.templates.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Master Template Form
            </a>

            <!-- Submissions Link -->
            <a href="{{ route('admin.submissions.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Data Pengajuan (User)
            </a>

            <div class="pt-6 border-t border-slate-800 mt-6 space-y-1.5">
                <!-- Portal Link -->
                <a href="{{ route('home') }}" target="_blank"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-800 hover:text-white transition-all duration-150">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Lihat Portal Form
                </a>

                <!-- Logout Link -->
                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-all duration-150 text-left">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </nav>

        <!-- Footer sidebar -->
        <div class="p-6 text-center text-[10px] text-slate-600 border-t border-slate-800">
            &copy; {{ date('Y') }} PT Dahana STI
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 shadow-sm z-10">
            <h1 class="text-lg font-bold text-slate-800 title-font">
                @yield('page_title', 'Admin Dashboard')
            </h1>

            <div class="flex items-center gap-3">
                <a href="{{ route('profile') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 transition">
                    <div class="w-9 h-9 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>

                    <div class="text-left">
                        <div class="text-xs text-slate-500">
                            Administrator
                        </div>
                        <div class="text-sm font-semibold text-slate-700">
                            {{ auth()->user()->name }}
                        </div>
                    </div>
                </a>
            </div>

        </header>

        <!-- Dynamic Content -->
        <main class="flex-grow p-8 overflow-y-auto max-w-7xl w-full mx-auto">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>

</html>