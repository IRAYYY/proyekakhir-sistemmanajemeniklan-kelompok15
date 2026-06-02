// TOAST
setTimeout(() => {

    const success =
    document.getElementById('toastSuccess');

    const error =
    document.getElementById('toastError');

    if(success) success.style.display = 'none';

    if(error) error.style.display = 'none';

}, 3000);

// LOADING
function showLoading() {

    document
    .getElementById('loadingOverlay')
    .classList.remove('hidden');

    document
    .getElementById('loadingOverlay')
    .classList.add('flex');
}

// MODAL
function openModal(link) {

    document
    .getElementById('confirmModal')
    .classList.remove('hidden');

    document
    .getElementById('confirmModal')
    .classList.add('flex');

    document
    .getElementById('confirmDeleteBtn')
    .href = link;
}

function closeModal() {

    document
    .getElementById('confirmModal')
    .classList.remove('flex');

    document
    .getElementById('confirmModal')
    .classList.add('hidden');
}