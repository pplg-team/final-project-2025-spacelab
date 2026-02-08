/**
 * Student Index Page - AJAX-based data fetching with filters
 */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const searchInput = document.getElementById('searchInput');
    const majorFilter = document.getElementById('majorFilter');
    const classFilter = document.getElementById('classFilter');
    const studentTableBody = document.getElementById('studentTableBody');
    const showingCount = document.getElementById('showingCount');
    const totalCount = document.getElementById('totalCount');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const noResultsState = document.getElementById('noResultsState');

    // Get data from window object (passed from Blade)
    const classroomsByMajor = window.studentIndexData?.classroomsByMajor || {};
    const fetchUrl = window.studentIndexData?.fetchUrl || '';

    // Debounce function
    let debounceTimer;
    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Update class filter when major changes
    majorFilter.addEventListener('change', function () {
        const selectedMajor = this.value;
        classFilter.innerHTML = '<option value="">Semua Kelas</option>';

        if (selectedMajor && classroomsByMajor[selectedMajor]) {
            classroomsByMajor[selectedMajor].forEach(classroom => {
                const option = document.createElement('option');
                option.value = classroom.id;
                option.textContent = classroom.name;
                classFilter.appendChild(option);
            });
        }

        fetchStudents();
    });

    // Handle import modal major-classroom dependency
    const importMajorSelect = document.getElementById('importMajorSelect');
    const importClassroomSelect = document.getElementById('importClassroomSelect');

    if (importMajorSelect && importClassroomSelect) {
        importMajorSelect.addEventListener('change', function () {
            const selectedMajor = this.value;

            // Reset classroom select
            importClassroomSelect.innerHTML = '<option value="">Pilih Kelas</option>';

            if (selectedMajor && classroomsByMajor[selectedMajor]) {
                // Enable classroom select and populate options
                importClassroomSelect.disabled = false;
                classroomsByMajor[selectedMajor].forEach(classroom => {
                    const option = document.createElement('option');
                    option.value = classroom.id;
                    option.textContent = classroom.name;
                    importClassroomSelect.appendChild(option);
                });
            } else {
                // Disable classroom select if no major selected
                importClassroomSelect.disabled = true;
            }
        });
    }

    // Fetch students from API
    async function fetchStudents() {
        const searchTerm = searchInput.value.trim();
        const selectedMajor = majorFilter.value;
        const selectedClass = classFilter.value;

        // Only fetch if there's a filter applied
        if (!searchTerm && !selectedMajor && !selectedClass) {
            showEmptyState();
            return;
        }

        showLoadingState();

        try {
            const params = new URLSearchParams();
            if (searchTerm) params.append('search', searchTerm);
            if (selectedMajor) params.append('major_id', selectedMajor);
            if (selectedClass) params.append('class_id', selectedClass);

            const response = await fetch(`${fetchUrl}?${params.toString()}`);
            const data = await response.json();

            renderStudents(data);
        } catch (error) {
            console.error('Error fetching students:', error);
            showNoResultsState();
        }
    }

    // Show loading state
    function showLoadingState() {
        hideAllStates();
        loadingState.style.display = '';
    }

    // Show empty state
    function showEmptyState() {
        hideAllStates();
        emptyState.style.display = '';
        showingCount.textContent = '0';
        totalCount.textContent = '0';
    }

    // Show no results state
    function showNoResultsState() {
        hideAllStates();
        noResultsState.style.display = '';
        showingCount.textContent = '0';
    }

    // Hide all states
    function hideAllStates() {
        loadingState.style.display = 'none';
        emptyState.style.display = 'none';
        noResultsState.style.display = 'none';

        // Remove existing student rows
        const existingRows = studentTableBody.querySelectorAll('.student-row');
        existingRows.forEach(row => row.remove());
    }

    // Render students
    function renderStudents(data) {
        hideAllStates();

        if (!data.students || data.students.length === 0) {
            showNoResultsState();
            return;
        }

        // Update counts
        showingCount.textContent = data.showing;
        totalCount.textContent = data.total;

        // Render each student
        data.students.forEach(student => {
            const row = createStudentRow(student);
            studentTableBody.appendChild(row);
        });
    }

    // Create student row
    function createStudentRow(student) {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors student-row';

        // Avatar
        let avatarHtml = '';
        if (student.avatar) {
            avatarHtml = `<img class="h-8 w-8 rounded-full object-cover" src="/storage/${student.avatar}" alt="">`;
        } else {
            avatarHtml = `
                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                        ${student.name.charAt(0).toUpperCase()}
                    </span>
                </div>
            `;
        }

        row.innerHTML = `
            <td class="px-4 py-3 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        ${avatarHtml}
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${escapeHtml(student.name)}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                ${escapeHtml(student.email)}
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                ${escapeHtml(student.nis)}
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                ${escapeHtml(student.nisn)}
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    ${escapeHtml(student.major_code)}
                </span>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                ${escapeHtml(student.class_name)}
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                <div class="flex items-center gap-2">
                    <button onclick="viewStudent('${student.id}')"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Lihat
                    </button>
                    <button onclick="editStudent('${student.id}')"
                        class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 dark:text-white text-xs font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </button>
                    <button onclick="deleteStudent('${student.id}', '${escapeHtml(student.name)}')"
                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus
                    </button>
                </div>
            </td>
        `;

        return row;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Event listeners
    searchInput.addEventListener('input', debounce(fetchStudents, 500));
    classFilter.addEventListener('change', fetchStudents);

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
    window.viewStudent = viewStudent;
    window.editStudent = editStudent;
    window.deleteStudent = deleteStudent;

    // View student details
    async function viewStudent(studentId) {
        try {
            const response = await fetch(`/admin/students/${studentId}`);
            const student = await response.json();

            if (student.error) {
                alert('Gagal memuat data siswa');
                return;
            }

            // Build view content
            const content = `
                <div class="space-y-4">
                    ${student.avatar ? `
                        <div class="flex justify-center">
                            <img src="/storage/${student.avatar}" alt="${student.name}"
                                class="w-24 h-24 rounded-full object-cover">
                        </div>
                    ` : ''}

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.email}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">NIS</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.nis || '-'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">NISN</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.nisn}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jurusan</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.major} (${student.major_code})</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kelas</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.classroom}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Blok</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.block}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Ajaran</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${student.term}</p>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('viewStudentContent').innerHTML = content;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-student-modal' }));
        } catch (error) {
            console.error('Error viewing student:', error);
            alert('Gagal memuat data siswa');
        }
    }

    // Edit student
    async function editStudent(studentId) {
        try {
            const response = await fetch(`/admin/students/${studentId}`);
            const student = await response.json();

            if (student.error) {
                alert('Gagal memuat data siswa');
                return;
            }

            // Populate form
            document.getElementById('editStudentId').value = student.id;
            document.getElementById('editName').value = student.name;
            document.getElementById('editEmail').value = student.email;
            document.getElementById('editNis').value = student.nis || '';
            document.getElementById('editNisn').value = student.nisn;
            document.getElementById('editClassroom').value = student.classroom_id;

            const avatarPreview = document.getElementById('avatarPreview');

            if (student.avatar) {
                if (avatarPreview) {
                    avatarPreview.classList.remove('hidden');
                    document.getElementById('avatarPreviewImg').src = `/storage/${student.avatar}`;
                }
            } else {
                if (avatarPreview) avatarPreview.classList.add('hidden');
            }

            // Set form action
            document.getElementById('editStudentForm').action = `/admin/students/${student.id}`;

            // Open modal
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-student-modal' }));
        } catch (error) {
            console.error('Error loading student for edit:', error);
            alert('Gagal memuat data siswa');
        }
    }

    // Delete student
    function deleteStudent(studentId, studentName) {
        document.getElementById('deleteStudentId').value = studentId;
        document.getElementById('deleteStudentName').textContent = studentName;
        document.getElementById('deleteStudentForm').action = `/admin/students/${studentId}`;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-student-modal' }));
    }

    // preview avatar
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const avatarPreview = document.getElementById('avatarPreview');
                    if (avatarPreview) {
                        avatarPreview.classList.remove('hidden');
                        document.getElementById('avatarPreviewImg').src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // preview avatar for add student form
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
});
