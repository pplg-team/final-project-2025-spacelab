/**
 * Staff Management JavaScript
 */

// Search functionality
const searchInput = document.getElementById('searchInput');
const staffRows = document.querySelectorAll('.staff-row');
const noResultsState = document.getElementById('noResultsState');
const emptyState = document.getElementById('emptyState');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        staffRows.forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';

            const matches = name.includes(searchTerm) || email.includes(searchTerm);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        // Show/hide no results state
        if (noResultsState) {
            noResultsState.style.display = (visibleCount === 0 && staffRows.length > 0) ? '' : 'none';
        }

        // Hide empty state during search if there are rows
        if (emptyState && staffRows.length > 0) {
            emptyState.style.display = 'none';
        }
    });
}

// View Staff
window.viewStaff = async function (id) {
    try {
        const response = await fetch(`/admin/staff/${id}`);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Gagal memuat data');
        }

        const content = document.getElementById('viewStaffContent');
        content.innerHTML = `
            <div class="flex items-center mb-4">
                <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xl font-medium text-gray-600 dark:text-gray-400">
                    ${data.name ? data.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase() : 'S'}
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">${data.name || '-'}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">${data.role || 'Staff'}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">${data.email || '-'}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Terdaftar</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">${data.created_at || '-'}</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Login Terakhir</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">${data.last_login_at || 'Belum pernah login'}</p>
                </div>
            </div>
        `;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-staff-modal' }));
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat data staff: ' + error.message);
    }
};

// Edit Staff
window.editStaff = async function (id) {
    try {
        const response = await fetch(`/admin/staff/${id}`);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Gagal memuat data');
        }

        document.getElementById('editStaffId').value = id;
        document.getElementById('editName').value = data.name || '';
        document.getElementById('editEmail').value = data.email || '';
        document.getElementById('editPassword').value = '';
        document.getElementById('editPasswordConfirmation').value = '';

        document.getElementById('editStaffForm').action = `/admin/staff/${id}`;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-staff-modal' }));
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat data staff: ' + error.message);
    }
};

// Reset Password
window.resetPassword = function (id, name) {
    document.getElementById('resetPasswordStaffName').textContent = name;
    document.getElementById('resetPasswordForm').action = `/admin/staff/${id}/reset-password`;

    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'reset-password-modal' }));
};

// Delete Staff
window.deleteStaff = function (id, name) {
    document.getElementById('deleteStaffName').textContent = name;
    document.getElementById('deleteStaffForm').action = `/admin/staff/${id}`;

    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-staff-modal' }));
};

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function () {
    const alerts = ['successAlert', 'errorAlert', 'validationAlert'];
    alerts.forEach(alertId => {
        const alert = document.getElementById(alertId);
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        }
    });
});
