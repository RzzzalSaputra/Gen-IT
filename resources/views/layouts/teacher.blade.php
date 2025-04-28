<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Teacher Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Mobile Navigation Toggle -->
        <div class="lg:hidden fixed top-0 left-0 right-0 bg-gray-800 z-30 px-4 py-3 flex items-center justify-between border-b-4 border-gray-900 dark:border-gray-600">
            <div class="flex items-center">
                <button id="mobile-menu-toggle" class="text-white p-2 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <a href="{{ route('teacher.dashboard') }}" class="text-white text-xl font-black ml-2">
                    <span class="text-blue-500">Gen</span>-IT Teacher
                </a>
            </div>
            <div>
                <span class="text-sm font-bold text-gray-300">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Sidebar - Hidden on mobile, visible on desktop -->
        <div id="sidebar" class="fixed inset-y-0 left-0 bg-gray-800 w-64 overflow-y-auto z-10 border-r-4 border-gray-900 dark:border-gray-600 shadow-[4px_0px_0px_0px_rgba(0,0,0,0.5)] transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">
            <div class="flex items-center justify-center h-16 border-b-4 border-gray-900 dark:border-gray-600">
                <a href="{{ route('teacher.dashboard') }}" class="text-white text-xl font-black">
                    <span class="text-blue-500">Gen</span>-IT Teacher
                </a>
            </div>
            <nav class="mt-5 px-2">
                <a href="{{ route('teacher.dashboard') }}" class="group flex items-center px-3 py-3 mb-3 text-base font-bold rounded-none text-white border-2 {{ request()->routeIs('teacher.dashboard') ? 'bg-gray-900 border-gray-600 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)]' : 'border-transparent hover:bg-gray-700 hover:border-gray-600 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)] hover:-translate-y-1 transition-transform' }}">
                    <svg class="mr-3 h-6 w-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('teacher.classrooms.index') }}" class="group flex items-center px-3 py-3 mb-3 text-base font-bold rounded-none text-white border-2 {{ request()->routeIs('teacher.classrooms.*') ? 'bg-gray-900 border-gray-600 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)]' : 'border-transparent hover:bg-gray-700 hover:border-gray-600 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)] hover:-translate-y-1 transition-transform' }}">
                    <svg class="mr-3 h-6 w-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    My Classrooms
                </a>
            </nav>
            
            <div class="absolute bottom-0 left-0 right-0 px-4 py-4 border-t-4 border-gray-900 dark:border-gray-600 bg-gray-800">
                <div class="flex items-center">
                    <div>
                        <p class="text-sm font-black text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-bold text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-base font-bold rounded-none text-white bg-red-600 hover:bg-red-700 border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)] hover:-translate-y-1 transition-transform">
                            <svg class="mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content - Responsive padding -->
        <div class="lg:ml-64 w-auto transition-all duration-300">
            <!-- Top Navigation - Hidden on mobile -->
            <div class="hidden lg:block bg-white dark:bg-gray-800 border-b-4 border-gray-900 dark:border-gray-700">
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-black text-gray-900 dark:text-white">@yield('header', 'Teacher Dashboard')</h1>
                    </div>
                    <div>
                        <a href="{{ url('/') }}" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 border-b-2 border-transparent hover:border-blue-500">
                            Back to Main Site
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Header -->
            <div class="lg:hidden bg-white dark:bg-gray-800 border-b-4 border-gray-900 dark:border-gray-700 mt-16">
                <div class="px-4 py-3">
                    <h1 class="text-xl font-black text-gray-900 dark:text-white">@yield('header', 'Teacher Dashboard')</h1>
                </div>
            </div>
            
            <!-- Page Content - Responsive padding -->
            <main class="py-6 px-4 sm:px-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-4 border-green-500 text-green-700 px-4 py-3 rounded-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)]">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-4 border-red-500 text-red-700 px-4 py-3 rounded-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)]">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Navigation Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-0 lg:hidden hidden" aria-hidden="true"></div>

    <script>
        // Mobile navigation toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (mobileMenuToggle && sidebar && backdrop) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    backdrop.classList.toggle('hidden');
                });
                
                backdrop.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>