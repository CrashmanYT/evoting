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
                    <h1 class="text-2xl font-bold pb-6 justify-center flex">Create Candidate</h1>
                    <form action="{{ route('dashboard.candidates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <x-bladewind::input name="no_urut" label="No Urut" numeric="true" required="true"
                                            onfocus="changeCss('.nourut', '!border-2,!border-red-400')"
                                            onblur="changeCss('.nourut', '!border-2,!border-red-400', 'remove')"
                                            class="p-6"
                        />
                        <x-bladewind::input name="name" label="Nama" required="true"
                                            onfocus="changeCss('.name', '!border-2,!border-red-400')"
                                            onblur="changeCss('.name', '!border-2,!border-red-400', 'remove')"  />
                        <x-bladewind::input name="description" label="Deskripsi" />
                        <x-bladewind::filepicker
                            name="photo_url"
                            placeholder="Upload a Photo"
                            accepted_file_types="image/*"  />

                        <x-bladewind::button
                            class="mx-auto block mt-6"
                            can_submit="true"
                            color="purple">
                            Add Candidate
                        </x-bladewind::button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
