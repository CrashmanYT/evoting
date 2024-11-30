{{-- Single Delete Modal --}}
<x-bladewind::modal 
    title="Anda Yakin?" 
    type="warning" 
    name="delete-warning"
    icon="exclamation-triangle" 
    ok_button_action="deleteParticipant()"
>
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

{{-- Delete All Modal --}}
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

{{-- Action Buttons --}}
<div class="flex justify-between items-center mb-4 mt-6">
    <div class="flex space-x-4">
        <x-bladewind::button 
            tag="a" 
            href="{{ route('admin.participants.create') }}" 
            size="tiny"
        >
            Tambah Peserta
        </x-bladewind::button>
    </div>
    <x-bladewind::button 
        type="button" 
        size="tiny" 
        color="red" 
        onclick="confirmDeleteAll()"
    >
        Hapus Semua Data
    </x-bladewind::button>
</div>

{{-- CSRF Token --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
