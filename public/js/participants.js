let participantToDelete = null;
let participantHasVoted = false;

function showModalWithParticipantId(modalName, participantId, hasVoted = false) {
    participantToDelete = participantId;
    participantHasVoted = hasVoted;
    
    const forceDeleteSection = document.getElementById('forceDeleteSection');
    if (forceDeleteSection) {
        forceDeleteSection.style.display = hasVoted ? 'block' : 'none';
    }
    
    showModal(modalName);
}

function redirectToEdit(id) {
    window.location.href = `/admin/participants/${id}/edit`;
}

function confirmFinalDeleteAll() {
    const checkbox = document.getElementById('confirmDeleteAll');
    const forceDelete = document.getElementById('forceDelete');
    
    if (!checkbox.checked) {
        alert('Harap centang kotak konfirmasi untuk melanjutkan');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/participants/delete-all';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    if (forceDelete && forceDelete.checked) {
        const forceDeleteInput = document.createElement('input');
        forceDeleteInput.type = 'hidden';
        forceDeleteInput.name = 'force_delete';
        forceDeleteInput.value = '1';
        form.appendChild(forceDeleteInput);
    }
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

function deleteParticipant() {
    if (!participantToDelete) return;

    if (participantHasVoted) {
        const forceDeleteCheckbox = document.getElementById('confirmForceDelete');
        if (!forceDeleteCheckbox.checked) {
            alert('Harap centang kotak konfirmasi untuk menghapus paksa');
            return;
        }
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/participants/${participantToDelete}`;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    if (participantHasVoted) {
        const forceDeleteInput = document.createElement('input');
        forceDeleteInput.type = 'hidden';
        forceDeleteInput.name = 'force_delete';
        forceDeleteInput.value = '1';
        form.appendChild(forceDeleteInput);
    }

    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}
