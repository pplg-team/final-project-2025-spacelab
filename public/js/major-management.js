
// Helper to toggle modal via Alpine events
window.openModal = function (mode) {
    const form = document.getElementById('majorForm');
    const title = document.getElementById('modalTitle');

    if (mode === 'create') {
        if (title) title.textContent = 'Tambah Jurusan';

        // Retrieve store route from data attribute
        const storeRoute = form.dataset.storeRoute;
        if (storeRoute) form.action = storeRoute;

        form.reset();
        document.getElementById('formMethod').value = 'POST';
        const logoPreview = document.getElementById('logoPreview');
        if (logoPreview) logoPreview.classList.add('hidden');
    }

    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'majorModal' }));
}

window.editMajor = function (major) {
    const form = document.getElementById('majorForm');
    const title = document.getElementById('modalTitle');

    if (title) title.textContent = 'Edit Jurusan';
    form.action = `/${window.location.pathname.split('/')[1]}/majors/${major.id}`;
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('code').value = major.code;
    document.getElementById('name').value = major.name;
    document.getElementById('description').value = major.description || '';
    document.getElementById('slogan').value = major.slogan || '';
    document.getElementById('contact_email').value = major.contact_email || '';
    document.getElementById('website').value = major.website || '';

    const logoPreview = document.getElementById('logoPreview');
    if (major.logo) {
        if (logoPreview) {
            logoPreview.classList.remove('hidden');
            document.getElementById('logoPreviewImg').src = `/storage/${major.logo}`;
        }
    } else {
        if (logoPreview) logoPreview.classList.add('hidden');
    }

    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'majorModal' }));
}

window.closeModal = function () {
    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'majorModal' }));
}

document.addEventListener('DOMContentLoaded', () => {
    // Preview logo
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const logoPreview = document.getElementById('logoPreview');
                    if (logoPreview) {
                        logoPreview.classList.remove('hidden');
                        document.getElementById('logoPreviewImg').src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
