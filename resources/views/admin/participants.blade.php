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
                            <div class="space-y-4">
                                <p class="font-bold text-red-600">Peringatan!</p>
                                <div id="deleteWarningMessage">
                                    Data yang anda hapus tidak dapat kembali.
                                    Apakah Anda Yakin?
                                </div>
                                <div id="forceDeleteSection" class="hidden mt-4">
                                    <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-4">
                                        <p class="text-red-700">Peserta ini sudah melakukan voting!</p>
                                    </div>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" id="confirmForceDelete" class="form-checkbox h-4 w-4 text-red-600">
                                        <span class="text-red-600 font-semibold">Saya ingin menghapus PAKSA data peserta ini termasuk data voting</span>
                                    </label>
                                </div>
                            </div>
                        </x-bladewind::modal>

                        <x-bladewind::modal
                            name="delete-all-warning"
                            title="⚠️ Peringatan Penghapusan Data"
                            type="error"
                            icon="exclamation-triangle"
                            ok_button_action="confirmFinalDeleteAll()"
                            ok_button_label="Ya, Saya Mengerti"
                            cancel_button_label="Batal"
                        >
                            <div class="space-y-4">
                                <p class="font-bold text-red-600">Peringatan Penting!</p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Semua data peserta akan dihapus secara permanen</li>
                                    <li>Tindakan ini tidak dapat dibatalkan</li>
                                </ul>
                                <div class="mt-4 space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" id="confirmDeleteAll" class="form-checkbox h-4 w-4 text-red-600">
                                        <span>Saya mengerti konsekuensi dari penghapusan data ini</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" id="forceDelete" class="form-checkbox h-4 w-4 text-red-600">
                                        <span class="text-red-600 font-semibold">Saya ingin menghapus PAKSA semua data termasuk peserta yang sudah melakukan voting</span>
                                    </label>
                                </div>
                            </div>
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
                            let participantHasVoted = false;

                            function showNotification(message, type = 'success') {
                                notify(message, type);
                            }

                            function redirectToEdit(participantId) {
                                window.location.href = `/admin/participants/edit/${participantId}`;
                            }

                            function showModalWithParticipantId(modalName, participantId) {
                                participantToDelete = participantId;
                                
                                // Cek status voting peserta
                                fetch(`/admin/participants/${participantId}/check-voted`, {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    participantHasVoted = data.has_voted;
                                    
                                    // Tampilkan/sembunyikan section force delete
                                    const forceDeleteSection = document.getElementById('forceDeleteSection');
                                    if (participantHasVoted) {
                                        forceDeleteSection.classList.remove('hidden');
                                        document.getElementById('confirmForceDelete').checked = false;
                                    } else {
                                        forceDeleteSection.classList.add('hidden');
                                    }
                                    
                                    showModal(modalName);
                                });
                            }

                            function confirmDeleteAll() {
                                showModal('delete-all-warning');
                                // Reset checkbox setiap kali modal dibuka
                                document.getElementById('confirmDeleteAll').checked = false;
                                document.getElementById('forceDelete').checked = false;
                            }

                            function confirmFinalDeleteAll() {
                                const checkbox = document.getElementById('confirmDeleteAll');
                                const forceDelete = document.getElementById('forceDelete');
                                
                                if (!checkbox.checked) {
                                    showNotification('Anda harus mencentang kotak konfirmasi terlebih dahulu', 'error');
                                    return;
                                }

                                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                
                                fetch('/admin/participants', {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        force_delete: forceDelete.checked
                                    })
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
                                        hideModal('delete-all-warning');
                                        showNotification(data.message || 'Gagal menghapus data peserta.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    hideModal('delete-all-warning');
                                    showNotification(error.message || 'Terjadi kesalahan saat menghapus data peserta.', 'error');
                                });
                            }

                            function deleteParticipant() {
                                if (!participantToDelete) return;

                                if (participantHasVoted) {
                                    const forceDeleteCheckbox = document.getElementById('confirmForceDelete');
                                    if (!forceDeleteCheckbox.checked) {
                                        showNotification('Anda harus mencentang konfirmasi untuk menghapus paksa peserta yang sudah voting', 'error');
                                        return;
                                    }
                                }

                                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                
                                fetch(`/admin/participants/${participantToDelete}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        force_delete: participantHasVoted
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json().then(err => Promise.reject(err));
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        hideModal('delete-warning');
                                        showNotification(data.message || 'Gagal menghapus peserta.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    hideModal('delete-warning');
                                    showNotification(error.message || 'Terjadi kesalahan saat menghapus peserta.', 'error');
                                });
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

                        <x-responsive-table 
                            :data="$participants"
                            columns="nis, name, class, voted"
                            :actions="$action_icons"
                        />

                        <div class="mt-4 px-4">
                            {{ $paginatedParticipants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endsection
    </x-app-layout>
