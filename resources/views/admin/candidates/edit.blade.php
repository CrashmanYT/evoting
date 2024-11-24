<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Candidate') }}
        </h2>
    </x-slot>


    <div class="py-12 dark">
        <div class="max-w-md mx-auto ">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" p-6 bg-gray-800 text-white">
                    <h1 class="text-2xl font-bold pb-6 justify-center flex">Edit Candidate</h1>
                    <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <x-bladewind::input name="no_urut" label="No Urut" numeric="true" required="true" class="p-6"
                            value="{{ $candidate->no_urut }}" />
                        <x-bladewind::input name="name" label="Nama" required="true"
                            value="{{ $candidate->name }}" />
                        <x-bladewind::input name="description" label="Deskripsi"
                            value="{{ $candidate->description }}" />
                        <x-bladewind::filepicker name="photo_url" placeholder="Upload a Photo"
                            accepted_file_types="image/*"
                            url="{{ Storage::disk('public')->url('candidates/') }}"
                            selected_value="{{ $candidate->photo_url }}" />

                        <x-bladewind::button class="mx-auto block mt-6" can_submit="true" color="purple">
                            Edit Candidate
                        </x-bladewind::button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
