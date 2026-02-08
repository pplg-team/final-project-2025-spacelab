<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Siswa') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manajemen Siswa</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola data siswa berdasarkan jurusan dan kelas
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <x-secondary-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'add-student-modal')">
                                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                                Tambah Siswa
                            </x-secondary-button>
                            <x-secondary-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'import-student-modal')">
                                <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-2" />
                                Import CSV
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div id="successAlert"
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-check-circle
                            class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" />
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div id="errorAlert"
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-x-circle class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 flex-shrink-0" />
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div id="validationAlert"
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <x-heroicon-o-exclamation-triangle
                            class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 mt-0.5 flex-shrink-0" />
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Terdapat kesalahan pada
                                input:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="searchInput" placeholder="Cari nama, email, NIS, atau NISN..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400 text-sm">
                        </div>
                        <div class="flex gap-2">
                            <select id="majorFilter"
                                class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400 text-sm">
                                <option value="">Pilih Jurusan</option>
                                @foreach ($majors as $major)
                                    <option value="{{ $major->id }}">{{ $major->code }}</option>
                                @endforeach
                            </select>
                            <select id="classFilter"
                                class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400 text-sm">
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    NIS
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    NISN
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jurusan
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Kelas
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody"
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Loading State -->
                            <tr id="loadingState" style="display: none;">
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <svg class="animate-spin h-8 w-8 text-gray-400 mx-auto mb-3"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Memuat data...</p>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr id="emptyState">
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <x-heroicon-o-user-group class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Pilih filter untuk
                                        melihat data siswa</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gunakan filter jurusan,
                                        kelas, atau pencarian untuk menampilkan siswa</p>
                                </td>
                            </tr>

                            <!-- No Results State -->
                            <tr id="noResultsState" style="display: none;">
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <x-heroicon-o-magnifying-glass class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada hasil
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Coba ubah filter atau kata
                                        kunci pencarian</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Info -->
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Menampilkan <span id="showingCount">0</span> dari <span id="totalCount">0</span> siswa
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <x-modal name="add-student-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Tambah Siswa Baru') }}
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <x-text-input name="name" type="text" class="block w-full" :value="old('name')" required
                        autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <x-text-input name="email" type="email" class="block w-full" :value="old('email')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIS</label>
                        <x-text-input name="nis" type="text" class="block w-full" :value="old('nis')" />
                        <x-input-error class="mt-2" :messages="$errors->get('nis')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NISN</label>
                        <x-text-input name="nisn" type="text" class="block w-full" :value="old('nisn')"
                            required />
                        <x-input-error class="mt-2" :messages="$errors->get('nisn')" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                    <x-select-input name="classroom_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach ($classrooms as $class)
                            <option value="{{ $class->id }}"
                                {{ old('classroom_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->full_name }}
                            </option>
                        @endforeach
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('classroom_id')" />
                </div>

                <!-- Avatar -->
                <div>
                    <x-input-label for="addAvatar" value="Avatar" />
                    <input type="file" name="avatar" id="addAvatar" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-gray-100 file:text-gray-700
                            hover:file:bg-gray-200
                            dark:file:bg-gray-700 dark:file:text-gray-300
                            dark:hover:file:bg-gray-600">
                    <div id="addAvatarPreview" class="mt-2 hidden">
                        <img id="addAvatarPreviewImg" src="" alt="Preview"
                            class="h-20 w-20 rounded object-cover">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div
                    class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 -mx-6 -mb-6 mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-gray-800 dark:bg-gray-200 px-4 py-2 text-sm font-semibold text-white dark:text-gray-800 shadow-sm hover:bg-gray-700 dark:hover:bg-gray-300 sm:ml-3 sm:w-auto">
                        Simpan
                    </button>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Import Student Modal -->
    <x-modal name="import-student-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Import Siswa via CSV') }}
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Pastikan CSV mengikuti struktur template. Duplikat email atau NISN akan dilewati.
            </p>

            <div class="mb-4">
                <a href="{{ route('admin.students.template') }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                    Download Template
                </a>
            </div>

            <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
                    <select id="importMajorSelect" name="major_id" required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <option value="">Pilih Jurusan</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}"
                                {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('major_id')" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                    <select id="importClassroomSelect" name="classroom_id" required disabled
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">Pilih Kelas</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('classroom_id')" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File CSV</label>
                    <input type="file" name="file" accept=".csv, .txt" required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    <x-input-error class="mt-2" :messages="$errors->get('file')" />
                </div>

                <div
                    class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 -mx-6 -mb-6 mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-gray-800 dark:bg-gray-200 px-4 py-2 text-sm font-semibold text-white dark:text-gray-800 shadow-sm hover:bg-gray-700 dark:hover:bg-gray-300 sm:ml-3 sm:w-auto">
                        Import
                    </button>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- View Student Modal -->
    <x-modal name="view-student-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Detail Siswa
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <div id="viewStudentContent" class="space-y-4">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </x-modal>

    <!-- Edit Student Modal -->
    <x-modal name="edit-student-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Edit Siswa
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form id="editStudentForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editStudentId" name="student_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <x-text-input id="editName" name="name" type="text" class="block w-full" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <x-text-input id="editEmail" name="email" type="email" class="block w-full" required />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIS</label>
                        <x-text-input id="editNis" name="nis" type="text" class="block w-full" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NISN</label>
                        <x-text-input id="editNisn" name="nisn" type="text" class="block w-full" required />
                    </div>
                </div>

                <!-- Avatar -->
                <div>
                    <x-input-label for="avatar" value="Avatar" />
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-gray-100 file:text-gray-700
                            hover:file:bg-gray-200
                            dark:file:bg-gray-700 dark:file:text-gray-300
                            dark:hover:file:bg-gray-600">
                    <div id="avatarPreview" class="mt-2 hidden">
                        <img id="avatarPreviewImg" src="" alt="Preview"
                            class="h-20 w-20 rounded object-cover">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                    <x-select-input id="editClassroom" name="classroom_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach ($classrooms as $class)
                            <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                        @endforeach
                    </x-select-input>
                </div>

                <div
                    class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 -mx-6 -mb-6 mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-gray-800 dark:bg-gray-200 px-4 py-2 text-sm font-semibold text-white dark:text-gray-800 shadow-sm hover:bg-gray-700 dark:hover:bg-gray-300 sm:ml-3 sm:w-auto">
                        Simpan Perubahan
                    </button>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-student-modal" focusable>
        <div class="p-6">
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Hapus Siswa
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus siswa <span id="deleteStudentName"
                            class="font-semibold"></span>?
                        Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait siswa ini.
                    </p>
                </div>
            </div>

            <form id="deleteStudentForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="deleteStudentId">

                <div
                    class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 -mx-6 -mb-6 mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                        Hapus
                    </button>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </x-modal>


    <script>
        // Pass data from Blade to JavaScript module
        window.studentIndexData = {
            classroomsByMajor: @json(
                $classrooms->groupBy('major_id')->map(function ($classes) {
                    return $classes->map(function ($class) {
                            return ['id' => $class->id, 'name' => $class->full_name];
                        })->sortBy('name')->values();
                })),
            fetchUrl: '{{ route('admin.students.fetch') }}'
        };
    </script>
    @vite(['resources/js/admin/student-index.js'])
</x-app-layout>
