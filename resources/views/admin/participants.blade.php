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
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl font-bold mb-4">Data Peserta</h2>

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

                        <div class="mb-8 mt-6">
                            <x-bladewind::card>
                                <h3 class="text-lg font-semibold mb-4">Import Data Peserta</h3>
                                <form action="{{ route('admin.participants.import') }}" 
                                    method="POST" 
                                    enctype="multipart/form-data" 
                                    class="space-y-4">
                                    @csrf
                                    <div class="space-y-4">
                                        <x-bladewind::filepicker
                                            name="filepicker"
                                            placeholder="Pilih file Excel"
                                            accepted_file_types=".xlsx,.xls"
                                            required="true"
                                        />
                                        
                                        @error('filepicker')
                                            <div class="text-red-500 text-sm mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <x-bladewind::alert type="info" show_close_icon="false">
                                            Format file Excel harus memiliki kolom: nis, name, class
                                        </x-bladewind::alert>

                                        <div class="mt-4">
                                            <x-bladewind::button
                                                type="primary"
                                                can_submit="true"
                                            >
                                                Import Data
                                            </x-bladewind::button>
                                        </div>
                                    </div>
                                </form>
                            </x-bladewind::card>
                        </div>

                        <x-bladewind::modal title="Anda Yakin?" type="warning" name="delete-warning"
                            icon="exclamation-triangle" ok_button_action="deleteParticipant()">
                            Data yang anda hapus tidak dapat kembali.
                            Apakah Anda Yakin?
                        </x-bladewind::modal>

                        <x-bladewind::modal
                            name="delete-all-warning"
                            title="Hapus Semua Data"
                            type="error"
                            icon="exclamation-triangle"
                            ok_button_action="deleteAllParticipants()"
                        >
                            Semua data peserta akan dihapus dan tidak dapat dikembalikan.
                            Apakah Anda yakin ingin menghapus semua data?
                        </x-bladewind::modal>
                        <div class="flex justify-between items-center mb-4 mt-6">
                            <div class="flex space-x-4">
                                <x-bladewind::button tag="a" href="{{ route('admin.participants.create') }}" size="tiny">Tambah
                                    Peserta</x-bladewind::button>
                            </div>
                            <x-bladewind::button type="button" size="tiny" color="red" onclick="confirmDeleteAll()">
                                Hapus Semua Data</x-bladewind::button>
                        </div>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        
                        <script>
                            let participantToDelete = null;

                            function showNotification(message, type = 'success') {
                                notify(message, type);
                            }

                            function redirectToEdit(participantId) {
                                window.location.href = `/admin/participants/edit/${participantId}`;
                            }

                            function showModalWithParticipantId(modalName, participantId) {
                                participantToDelete = participantId;
                                showModal(modalName);
                            }

                            function confirmDeleteAll() {
                                showModal('delete-all-warning');
                            }

                            function deleteAllParticipants() {
                                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                
                                fetch('/admin/participants', {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json().then(err => Promise.reject(err));
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        sessionStorage.setItem('success_message', data.message);
                                        location.reload();
                                    } else {
                                        alert(data.message || 'Gagal menghapus semua data peserta.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert(error.message || 'Terjadi kesalahan saat menghapus semua data peserta.');
                                })
                                .finally(() => {
                                    hideModal('delete-all-warning');
                                });
                            }

                            function deleteParticipant() {
                                if (participantToDelete) {
                                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                    
                                    fetch(`/admin/participants/${participantToDelete}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': token,
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            return response.json().then(err => Promise.reject(err));
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            // Set success message in session storage
                                            sessionStorage.setItem('success_message', data.message);
                                            location.reload();
                                        } else {
                                            alert(data.message || 'Gagal menghapus peserta.');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert(error.message || 'Terjadi kesalahan saat menghapus peserta.');
                                    })
                                    .finally(() => {
                                        hideModal('delete-warning');
                                    });
                                }
                            }

                            // Check for success message on page load
                            document.addEventListener('DOMContentLoaded', function() {
                                const successMessage = sessionStorage.getItem('success_message');
                                if (successMessage) {
                                    showNotification(successMessage, 'success');
                                    sessionStorage.removeItem('success_message');
                                }
                            });
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
