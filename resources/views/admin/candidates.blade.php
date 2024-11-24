<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Candidates') }}
        </h2>
    </x-slot>
    @section('content')
        <div class="py-12 dark">
            <div
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pt-6 pb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl font-bold mb-4">Data Kandidat</h2>

                        @if(session('success'))
                            <x-bladewind::alert type="success">
                                {{ session('success') }}
                            </x-bladewind::alert>
                        @endif

                        @if(session('error'))
                            <x-bladewind::alert type="error">
                                {{ session('error') }}
                            </x-bladewind::alert>
                        @endif

                        <div class="pb-6">
                            <a href="{{ route('admin.candidates.create') }}" class="">
                                <x-secondary-button>{{ __('Add') }}</x-secondary-button>
                            </a>
                        </div>
                        <x-bladewind::modal title="Anda Yakin?" type="warning" name="delete-warning"
                            icon="exclamation-triangle" ok_button_action="deleteCandidate()">
                            Data yang anda hapus tidak dapat kembali.
                            Apakah Anda Yakin?
                        </x-bladewind::modal>

                        <script>
                            let candidateToDelete = null;

                            function redirectToEdit(candidateId) {
                                window.location.href = `/admin/candidates/edit/${candidateId}`;
                            }

                            function showModalWithcandidateId(modalName, candidateId) {
                                candidateToDelete = candidateId;
                                showModal(modalName);
                            }

                            function deleteCandidate() {
                                if (candidateToDelete) {
                                    fetch(`{{ route('admin.candidates.destroy', ['candidate' => ':id']) }}`.replace(':id',
                                        candidateToDelete), {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    }).then(response => response.json()).then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert('Failed to delete candidates.');
                                        }
                                    }).catch(error => {
                                        console.error('Error:', error);
                                        alert('An error occurred while trying to delete the candidates.');
                                    });
                                }
                            }
                        </script>
                        <?php $action_icons = ["icon:pencil | click:redirectToEdit('{id}')", "icon:trash | color:red | click:showModalWithcandidateId('delete-warning', '{id}')"]; ?>

                        <x-bladewind::table searchable="true" include_columns="no_urut, name, description, photo_url" :data="$candidates"
                            :action_icons="$action_icons" has_border="true" no_data_message="No data found" divider="thin">
                        </x-bladewind::table>
                    </div>
                </div>
            </div>
        </div>

        {{-- {{ $participants->links() }} <!-- For pagination --> --}}
    @endsection
</x-app-layout>
