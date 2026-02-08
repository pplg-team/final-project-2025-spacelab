/**
 * Teacher Index Page - Client-side filtering and modal handling
 */

document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const searchInput = document.getElementById('searchInput');
    const subjectFilter = document.getElementById('subjectFilter');
    const teacherTableBody = document.getElementById('teacherTableBody');
    const showingCount = document.getElementById('showingCount');
    const noResultsState = document.getElementById('noResultsState');

    // Debounce function
    let debounceTimer;
    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Filter teachers
    function filterTeachers() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedSubject = subjectFilter.value;

        const rows = teacherTableBody.querySelectorAll('.teacher-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const code = row.dataset.code || '';
            const subjects = row.dataset.subjects || '';

            let matchesSearch = true;
            let matchesSubject = true;

            // Search filter
            if (searchTerm) {
                matchesSearch = name.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    code.includes(searchTerm);
            }

            // Subject filter
            if (selectedSubject) {
                const subjectIds = subjects.split(',');
                matchesSubject = subjectIds.includes(selectedSubject);
            }

            if (matchesSearch && matchesSubject) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update count
        showingCount.textContent = visibleCount;

        // Show/hide no results state
        if (visibleCount === 0 && rows.length > 0) {
            noResultsState.style.display = '';
        } else {
            noResultsState.style.display = 'none';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', debounce(filterTeachers, 300));
    subjectFilter.addEventListener('change', filterTeachers);

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = ['successAlert', 'errorAlert', 'validationAlert'];
        alerts.forEach(id => {
            const alert = document.getElementById(id);
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

    // Make functions globally accessible
    window.viewTeacher = viewTeacher;
    window.editTeacher = editTeacher;
    window.deleteTeacher = deleteTeacher;

    // View teacher details
    async function viewTeacher(teacherId) {
        try {
            const response = await fetch(`/admin/teachers/${teacherId}`);
            const teacher = await response.json();

            if (teacher.error) {
                alert('Gagal memuat data guru');
                return;
            }

            // Build view content
            let subjectsHtml = '-';
            if (teacher.subjects && teacher.subjects.length > 0) {
                subjectsHtml = teacher.subjects.map(s =>
                    `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 mr-1 mb-1">${s.name} (${s.code})</span>`
                ).join('');
            }

            let guardianHtml = '-';
            if (teacher.guardian_histories && teacher.guardian_histories.length > 0) {
                guardianHtml = teacher.guardian_histories.map(h =>
                    `<div class="text-sm">${h.classroom} (${h.started_at} - ${h.ended_at})</div>`
                ).join('');
            }

            let rolesHtml = '';
            if (teacher.role_assignments && teacher.role_assignments.length > 0) {
                rolesHtml += teacher.role_assignments.map(r =>
                    `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 mr-1 mb-1">${r.role} - ${r.major}</span>`
                ).join('');
            }
            if (teacher.coordinator_assignments && teacher.coordinator_assignments.length > 0) {
                rolesHtml += teacher.coordinator_assignments.map(r =>
                    `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 mr-1 mb-1">${r.role} - ${r.major}</span>`
                ).join('');
            }
            if (!rolesHtml) rolesHtml = '-';

            const content = `
                <div class="space-y-4">
                    ${teacher.avatar ? `
                        <div class="flex justify-center">
                            <img src="/storage/${teacher.avatar}" alt="${teacher.name}"
                                class="w-24 h-24 rounded-full object-cover">
                        </div>
                    ` : ''}

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${teacher.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${teacher.email}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kode</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${teacher.code || '-'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${teacher.phone || '-'}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Mata Pelajaran</label>
                        <div class="flex flex-wrap">${subjectsHtml}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Riwayat Wali Kelas</label>
                        <div>${guardianHtml}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Jabatan</label>
                        <div class="flex flex-wrap">${rolesHtml}</div>
                    </div>
                </div>
            `;

            document.getElementById('viewTeacherContent').innerHTML = content;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-teacher-modal' }));
        } catch (error) {
            console.error('Error viewing teacher:', error);
            alert('Gagal memuat data guru');
        }
    }

    // Edit teacher
    async function editTeacher(teacherId) {
        try {
            const response = await fetch(`/admin/teachers/${teacherId}`);
            const teacher = await response.json();

            if (teacher.error) {
                alert('Gagal memuat data guru');
                return;
            }

            // Populate form
            document.getElementById('editTeacherId').value = teacher.id;
            document.getElementById('editName').value = teacher.name;
            document.getElementById('editEmail').value = teacher.email;
            document.getElementById('editCode').value = teacher.code || '';
            document.getElementById('editPhone').value = teacher.phone || '';

            const avatarPreview = document.getElementById('editAvatarPreview');
            if (teacher.avatar) {
                if (avatarPreview) {
                    avatarPreview.classList.remove('hidden');
                    document.getElementById('editAvatarPreviewImg').src = `/storage/${teacher.avatar}`;
                }
            } else {
                if (avatarPreview) avatarPreview.classList.add('hidden');
            }

            // Set form action
            document.getElementById('editTeacherForm').action = `/admin/teachers/${teacher.id}`;

            // Open modal
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-teacher-modal' }));
        } catch (error) {
            console.error('Error loading teacher for edit:', error);
            alert('Gagal memuat data guru');
        }
    }

    // Delete teacher
    function deleteTeacher(teacherId, teacherName) {
        document.getElementById('deleteTeacherName').textContent = teacherName;
        document.getElementById('deleteTeacherForm').action = `/admin/teachers/${teacherId}`;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-teacher-modal' }));
    }

    // Preview avatar for add form
    const addAvatarInput = document.getElementById('addAvatar');
    if (addAvatarInput) {
        addAvatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const avatarPreview = document.getElementById('addAvatarPreview');
                    if (avatarPreview) {
                        avatarPreview.classList.remove('hidden');
                        document.getElementById('addAvatarPreviewImg').src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Preview avatar for edit form
    const editAvatarInput = document.getElementById('editAvatar');
    if (editAvatarInput) {
        editAvatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const avatarPreview = document.getElementById('editAvatarPreview');
                    if (avatarPreview) {
                        avatarPreview.classList.remove('hidden');
                        document.getElementById('editAvatarPreviewImg').src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
