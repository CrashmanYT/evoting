<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased dark">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        <!-- Page Content -->
        <main>
            <div class="py-12 dark">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6 lg:p-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
                                Pemilihan Calon Formatur IPM
                            </h2>
                            
                            <form method="POST" action="{{ route('vote.store') }}" class="space-y-8">
                                @csrf
                                
                                <div class="max-w-md mx-auto">
                                    <label class="block text-lg font-medium text-gray-900 dark:text-gray-100 mb-2" for="nis">
                                        NIS (Nomor Induk Siswa)
                                    </label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Masukkan NIS Anda untuk verifikasi voting
                                    </p>
                                    <x-bladewind::input 
                                        name="nis" 
                                        placeholder="Contoh: 12345"
                                        required="true" 
                                        class="mb-8" />
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 my-8"></div>

                                <div class="mb-6 mt-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 text-center">
                                        Daftar Kandidat
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 text-center">
                                        Pilih 7 kandidat yang menurut Anda paling tepat
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach ($candidates as $candidate)
                                        <x-bladewind::card compact="true" class="h-full">
                                            <div class="p-4 flex flex-col h-full">
                                                <div class="aspect-w-16 aspect-h-9 mb-4">
                                                    <img src="{{ asset('storage/candidates/' . $candidate->photo_url) }}" 
                                                        alt="{{ $candidate->name }}"
                                                        class="w-full h-48 object-cover rounded-lg">
                                                </div>
                                                
                                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                                    {{ $candidate->name }}
                                                </h3>
                                                
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 flex-grow">
                                                    {{ $candidate->description }}
                                                </p>
                                                
                                                <div class="mt-auto pt-4 text-white border-t border-gray-100 dark:border-gray-700">
                                                    <x-bladewind::checkbox 
                                                        name="candidate_ids[]" 
                                                        value="{{ $candidate->id }}"
                                                        label="Pilih Kandidat" />
                                                        
                                                </div>
                                            </div>
                                        </x-bladewind::card>
                                    @endforeach
                                </div>

                                <div class="mt-8 text-center">
                                    <x-bladewind::button 
                                        can_submit="true" 
                                        size="big"
                                        class="w-full md:w-auto">
                                        Submit Vote
                                    </x-bladewind::button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
