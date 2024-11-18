<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Participant') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="py-12">
        <div class="max-w-md mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('dashboard.participants.update', $participant->id) }}">
                        @csrf
                        <x-bladewind::input name="nis" label="No Urut" numeric="true"
                                            class="p-6" value="{{ $participant->nis }}"
                        />
                        <x-bladewind::input name="name" label="Nama"
                                             value="{{ $participant->name }}" />
                        <x-bladewind::input name="class" label="Kelas"
                                             value="{{ $participant->class }}" />
                        <x-bladewind::button
                            class="mx-auto block mt-6"
                            can_submit="true"
                            color="purple">
                            Edit Participant
                        </x-bladewind::button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
</x-app-layout>