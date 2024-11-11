<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Participant') }}
        </h2>

    </x-slot>
    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('dashboard.participants.store') }}">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="nis">NIS:</x-input-label>
                            <x-text-input type="text" name="nis" id="nis" required></x-text-input>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="name">Name:</x-input-label>
                            <x-text-input type="text" name="name" id="name" required></x-text-input>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="class">Class:</x-input-label>
                            <x-text-input type="text" name="class" id="class" required></x-text-input>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-primary-button>{{__('Add Participant')}}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
