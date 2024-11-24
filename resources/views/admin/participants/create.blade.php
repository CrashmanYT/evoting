<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Participant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-2 lg:px-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pt-6 pb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900 dark:text-gray-100 flex justify-center">
                    <form method="POST" action="{{ route('admin.participants.store') }}" class="dark">
                        @csrf
                        
                        @if(session('success'))
                            <div class="mb-4 text-green-600">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 text-red-600">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <x-input-label for="nis">NIS:</x-input-label>
                            <x-text-input class="w-full" type="text" name="nis" id="nis" value="{{ old('nis') }}" required></x-text-input>
                            @error('nis')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-input-label for="name">Name:</x-input-label>
                            <x-text-input class="w-full" type="text" name="name" id="name" value="{{ old('name') }}" required></x-text-input>
                            @error('name')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-input-label for="class">Class:</x-input-label>
                            <x-text-input class="w-full" type="text" name="class" id="class" value="{{ old('class') }}" required></x-text-input>
                            @error('class')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <x-primary-button>{{ __('Add Participant') }}</x-primary-button>
                            <a href="{{ route('admin.participants') }}" class="text-gray-400 hover:text-gray-500">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
