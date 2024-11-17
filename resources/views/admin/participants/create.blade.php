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
                    <form method="POST" action="{{ route('dashboard.participants.store') }}" class="dark">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="nis">NIS:</x-input-label>
                            <x-text-input class="w-full" type="text" name="nis" id="nis" required></x-text-input>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="name">Name:</x-input-label>
                            
                            <x-text-input type="text" name="name" id="name" required></x-text-input>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="class">Class:</x-input-label>
                            <x-text-input type="text" name="class" id="class" required></x-text-input>
                        </div>

                        <div class="mb-4">
                            <x-primary-button>{{__('Add Participant')}}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
