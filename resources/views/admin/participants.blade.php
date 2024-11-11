<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Participants') }}
        </h2>
    </x-slot>
    @section('content')
        <div class="py-12 dark">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pt-6 pb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
{{--                    <div class="p-6 text-gray-900 dark:text-gray-100">--}}
                    <div class="pb-6">
                        <a href="{{ route('dashboard.participants.create') }}" class="">
                            <x-secondary-button>{{ __('Add') }}</x-secondary-button>
                        </a>
                    </div>
                    <script>
                        function redirectToEdit(participantId) {
                            window.location.href = `/dashboard/participants/edit/${participantId}`;
                        }

                        function deleteParticipant(participantId) { if (confirm('Are you sure?')) { fetch(`{{ route('dashboard.participants.destroy', ['participant' => ':id']) }}`.replace(':id', participantId), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(response => { if (response.ok) { location.reload(); } else { alert('Failed to delete participant.'); } }); } }
                    </script>

                    <?php
                    $action_icons = [
                        "icon:pencil | click:redirectToEdit('{id}')",
                        "icon:trash | color:red | click:deleteParticipant('{id}')",
                    ];
                    ?>

                    <x-bladewind::table
                            searchable="true"
                            include_columns="nis, name, class"
                            :data="$participants"
                            :action_icons="$action_icons"
                            has_border="true"
                            no_data_message="No data found"
                            divider="thin"
                        >
                            <x-slot name="header">
                                <th>NIS</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Actions</th>
                            </x-slot>
                        </x-bladewind::table>
                    </div>
                </div>
            </div>

        {{-- {{ $participants->links() }} <!-- For pagination --> --}}
    @endsection
</x-app-layout>
