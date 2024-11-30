<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   

    <title>{{ config('app.name', 'E-Voting') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased dark:bg-gray-900">
    <div class="relative min-h-screen selection:bg-blue-500 selection:text-white dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center min-h-screen py-12">
                <!-- Logo atau Judul -->
                <x-bladewind::card class="dark:bg-gray-800 shadow-xl mb-8 w-full max-w-4xl">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold dark:text-white mb-4">E-Voting System</h1>
                        <div class="w-full h-px dark:bg-gray-600 my-4"></div>
                        <p class="text-xl dark:text-gray-300">
                            Selamat datang di sistem E-Voting. Silakan pilih opsi di bawah ini untuk melanjutkan.
                        </p>
                    </div>
                </x-bladewind::card>

                <!-- Tombol-tombol -->
                <div class="flex flex-row space-x-12 gap-4 w-full max-w-4xl mt-6">
                    <!-- Tombol Dashboard Admin -->
                    <x-bladewind::card 
                        has_shadow="true"
                        class="w-1/2 dark:bg-gray-800 hover:scale-105 transition-all duration-200 cursor-pointer">
                        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center text-center">
                            <span class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </span>
                            <h3 class="text-xl font-bold mb-2 dark:text-white">Dashboard Admin</h3>
                            <p class="text-gray-600 dark:text-gray-300">Kelola kandidat, peserta, dan lihat hasil voting secara real-time</p>
                        </a>
                    </x-bladewind::card>

                    <!-- Tombol Voting -->
                    <x-bladewind::card 
                        has_shadow="true"
                        class="w-1/2 dark:bg-gray-800 hover:scale-105 transition-all duration-200 cursor-pointer">
                        <a href="{{ route('vote') }}" class="flex flex-col items-center text-center">
                            <span class="bg-green-100 dark:bg-green-900 p-3 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            <h3 class="text-xl font-bold mb-2 dark:text-white">Halaman Voting</h3>
                            <p class="text-gray-600 dark:text-gray-300">Mulai voting dan pilih kandidat favorit Anda</p>
                        </a>
                    </x-bladewind::card>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
