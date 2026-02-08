<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Guru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manajemen Guru</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola data guru dan mata pelajaran yang diampu
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <x-secondary-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'add-teacher-modal')">
                                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                                Tambah Guru
                            </x-secondary-button>
                            <x-secondary-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'import-teacher-modal')">
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
                            <input type="text" id="searchInput" placeholder="Cari nama, email, atau kode guru..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400 text-sm">
                        </div>
                        <div class="flex gap-2">
                            <select id="subjectFilter"
                                class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400 text-sm">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers Table -->
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
                                    Kode
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Telepon
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Mata Pelajaran
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="teacherTableBody"
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($teachers as $teacher)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors teacher-row"
                                    data-name="{{ strtolower($teacher->user->name ?? '') }}"
                                    data-email="{{ strtolower($teacher->user->email ?? '') }}"
                                    data-code="{{ strtolower($teacher->code ?? '') }}"
                                    data-subjects="{{ $teacher->subjects->pluck('id')->join(',') }}">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($teacher->avatar)
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                        src="{{ asset('storage/' . $teacher->avatar) }}"
                                                        alt="">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                        <span
                                                            class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                            {{ strtoupper(substr($teacher->user->name ?? 'G', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $teacher->user->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $teacher->user->email ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $teacher->code ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $teacher->phone ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($teacher->subjects->take(3) as $subject)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                    {{ $subject->code }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-gray-400">-</span>
                                            @endforelse
                                            @if ($teacher->subjects->count() > 3)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                    +{{ $teacher->subjects->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button onclick="viewTeacher('{{ $teacher->id }}')"
                                                class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                                                title="Lihat Detail">
                                                <x-heroicon-o-eye class="w-5 h-5" />
                                            </button>
                                            <button onclick="editTeacher('{{ $teacher->id }}')"
                                                class="p-1.5 text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded-md transition-colors"
                                                title="Edit">
                                                <x-heroicon-o-pencil class="w-5 h-5" />
                                            </button>
                                            <button
                                                onclick="deleteTeacher('{{ $teacher->id }}', '{{ $teacher->user->name ?? 'Guru' }}')"
                                                class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                                                title="Hapus">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyState">
                                    <td colspan="6" class="px-4 py-12 text-center">
                                        <x-heroicon-o-user-group class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada data
                                            guru</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan guru baru
                                            untuk memulai</p>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- No Results State -->
                            <tr id="noResultsState" style="display: none;">
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <x-heroicon-o-magnifying-glass class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada hasil
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Coba ubah kata kunci
                                        pencarian</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Info -->
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Menampilkan <span id="showingCount">{{ $teachers->firstItem() ?? 0 }}</span> -
                            <span>{{ $teachers->lastItem() ?? 0 }}</span> dari <span
                                id="totalCount">{{ $teachers->total() }}</span> guru
                        </div>
                        <div>
                            {{ $teachers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <x-modal name="add-teacher-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Tambah Guru Baru') }}
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form method="POST" action="{{ route('admin.teachers.store') }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama *</label>
                    <x-text-input name="name" type="text" class="block w-full" :value="old('name')" required
                        autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                    <x-text-input name="email" type="email" class="block w-full" :value="old('email')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                            Guru</label>
                        <x-text-input name="code" type="text" class="block w-full" :value="old('code')" />
                        <x-input-error class="mt-2" :messages="$errors->get('code')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                        <x-text-input name="phone" type="text" class="block w-full" :value="old('phone')" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
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

    <!-- Import Teacher Modal -->
    <x-modal name="import-teacher-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Import Guru via CSV') }}
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Pastikan CSV mengikuti struktur template. Duplikat email akan dilewati.
            </p>

            <div class="mb-4">
                <a href="{{ route('admin.teachers.template') }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                    Download Template
                </a>
            </div>

            <form method="POST" action="{{ route('admin.teachers.import') }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

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

    <!-- View Teacher Modal -->
    <x-modal name="view-teacher-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Detail Guru
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <div id="viewTeacherContent" class="space-y-4">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </x-modal>

    <!-- Edit Teacher Modal -->
    <x-modal name="edit-teacher-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Edit Guru
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form id="editTeacherForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editTeacherId" name="teacher_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama *</label>
                    <x-text-input id="editName" name="name" type="text" class="block w-full" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                    <x-text-input id="editEmail" name="email" type="email" class="block w-full" required />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                            Guru</label>
                        <x-text-input id="editCode" name="code" type="text" class="block w-full" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                        <x-text-input id="editPhone" name="phone" type="text" class="block w-full" />
                    </div>
                </div>

                <!-- Avatar -->
                <div>
                    <x-input-label for="editAvatar" value="Avatar" />
                    <input type="file" name="avatar" id="editAvatar" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-gray-100 file:text-gray-700
                            hover:file:bg-gray-200
                            dark:file:bg-gray-700 dark:file:text-gray-300
                            dark:hover:file:bg-gray-600">
                    <div id="editAvatarPreview" class="mt-2 hidden">
                        <img id="editAvatarPreviewImg" src="" alt="Preview"
                            class="h-20 w-20 rounded object-cover">
                    </div>
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
    <x-modal name="delete-teacher-modal" focusable>
        <div class="p-6">
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Hapus Guru
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus guru <span id="deleteTeacherName"
                            class="font-semibold"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <form id="deleteTeacherForm" method="POST">
                @csrf
                @method('DELETE')

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

    @vite(['resources/js/admin/teacher-index.js'])
</x-app-layout>
