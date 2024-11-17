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
                    {{--                    <div class="p-6 text-gray-900 dark:text-gray-100"> --}}
                    <div class="pb-6">
                        <a href="{{ route('dashboard.participants.create') }}" class="">
                            <x-secondary-button>{{ __('Add') }}</x-secondary-button>
                        </a>
                    </div>
                    <x-bladewind::modal title="Anda Yakin?" type="warning" name="delete-warning"
                        icon="exclamation-triangle" ok_button_action="deleteParticipant()">
                        Data yang anda hapus tidak dapat kembali.
                        Apakah Anda Yakin?
                    </x-bladewind::modal>

                    <script>
                        let participantToDelete = null;

                        function redirectToEdit(participantId) {
                            window.location.href = `/dashboard/participants/edit/${participantId}`;
                        }

                        function showModalWithParticipantId(modalName, participantId) {
                            participantToDelete = participantId;
                            showModal(modalName);
                        }

                        function deleteParticipant() {
                            if (participantToDelete) {
                                fetch(`{{ route('dashboard.participants.destroy', ['participant' => ':id']) }}`.replace(':id',
                                    participantToDelete), {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(response => response.json()).then(data => {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        alert('Failed to delete participant.');
                                    }
                                }).catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while trying to delete the participant.');
                                });
                            }
                        }
                    </script>
                    <?php $action_icons = ["icon:pencil | click:redirectToEdit('{id}')", "icon:trash | color:red | click:showModalWithParticipantId('delete-warning', '{id}')"]; ?>

                    <x-bladewind::table searchable="true" include_columns="nis, name, class, voted" :data="$participants"
                        :action_icons="$action_icons" has_border="true" no_data_message="No data found" divider="thin">
                    </x-bladewind::table>
                </div>
            </div>
        </div>

        {{-- {{ $participants->links() }} <!-- For pagination --> --}}
    @endsection
</x-app-layout>
