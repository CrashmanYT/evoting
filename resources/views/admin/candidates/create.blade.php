<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Candidate') }}
        </h2>
    </x-slot>

    <div class="py-12 dark">
        <div class="max-w-md mx-auto">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-800 text-white">
                    <h1 class="text-2xl font-bold pb-6 justify-center flex">Create Candidate</h1>

                    @if(session('success'))
                        <div class="mb-4 text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <x-bladewind::input 
                                name="no_urut" 
                                label="No Urut" 
                                numeric="true" 
                                required="true"
                                value="{{ old('no_urut') }}"
                                class="p-6"
                            />
                            @error('no_urut')
                                <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-bladewind::input 
                                name="name" 
                                label="Nama" 
                                required="true"
                                value="{{ old('name') }}"
                            />
                            @error('name')
                                <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-bladewind::input 
                                name="description" 
                                label="Deskripsi"
                                value="{{ old('description') }}"
                            />
                            @error('description')
                                <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-bladewind::filepicker
                                name="photo_url"
                                placeholder="Upload a Photo"
                                accepted_file_types="image/*"
                            />
                            @error('photo_url')
                                <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <x-bladewind::button
                                can_submit="true"
                                color="purple">
                                Add Candidate
                            </x-bladewind::button>
                            
                            <a href="{{ route('admin.candidates') }}" class="text-gray-400 hover:text-gray-300">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
