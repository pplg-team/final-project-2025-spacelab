<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Jurusan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div>

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manajemen Jurusan</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola data jurusan</p>
                </div>
                <div>
                    <x-secondary-button onclick="openModal('create')">
                        <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                        Tambah Jurusan
                    </x-secondary-button>
                    <x-secondary-button x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'import-major-modal')">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-2" />
                        Import CSV
                    </x-secondary-button>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div
                            class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($majors as $major)
                            <div
                                class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200 relative group">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex-shrink-0">
                                        @if ($major->logo)
                                            <img src="{{ Storage::url($major->logo) }}" alt="{{ $major->name }}"
                                                class="h-12 w-12 rounded object-cover">
                                        @else
                                            <div
                                                class="h-12 w-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <x-heroicon-o-academic-cap
                                                    class="h-8 w-8 text-gray-400 dark:text-gray-500" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                            {{ $major->code }}
                                        </p>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                            {{ $major->name }}
                                        </h3>
                                    </div>
                                </div>

                                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300 mb-4">
                                    @if ($major->slogan)
                                        <p class="italic">"{{ $major->slogan }}"</p>
                                    @endif

                                    <div class="flex items-center">
                                        <x-heroicon-o-envelope class="h-4 w-4 mr-2" />
                                        <span class="truncate">{{ $major->contact_email ?? '-' }}</span>
                                    </div>

                                    @if ($major->website)
                                        <div class="flex items-center">
                                            <x-heroicon-o-globe-alt class="h-4 w-4 mr-2" />
                                            <a href="{{ $major->website }}" target="_blank"
                                                class="text-blue-600 dark:text-blue-400 hover:underline truncate">
                                                {{ $major->website }}
                                            </a>
                                        </div>
                                    @endif

                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        <span class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full mr-2">
                                            {{ $major->classes_count }} Kelas
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center space-x-2">

                                    <a href="{{ route('admin.majors.show', $major->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                                        Lihat
                                    </a>

                                    <div>
                                        <x-secondary-button onclick="editMajor({{ json_encode($major) }})"
                                            class="!px-3 !py-1 text-xs">
                                            Edit
                                        </x-secondary-button>

                                        <form action="{{ route('admin.majors.destroy', $major->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit" class="!px-3 !py-1 text-xs">
                                                Hapus
                                            </x-danger-button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-academic-cap class="mx-auto h-12 w-12 text-gray-400" />
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada jurusan
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan
                                    jurusan baru.</p>
                                <div class="mt-6">
                                    <button onclick="openModal('create')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <x-heroicon-o-plus class="h-5 w-5 mr-2" />
                                        Tambah Jurusan
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $majors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal name="majorModal" :show="false" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalTitle" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Tambah Jurusan
                </h2>
                <button x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form id="majorForm" method="POST" enctype="multipart/form-data"
                data-store-route="{{ route('admin.majors.store') }}">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="space-y-4">
                    <!-- Kode Jurusan -->
                    <div>
                        <x-input-label for="code" value="Kode Jurusan *" />
                        <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('code')" />
                    </div>

                    <!-- Nama Jurusan -->
                    <div>
                        <x-input-label for="name" value="Nama Jurusan *" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <x-input-label for="description" value="Deskripsi" />
                        <textarea id="description" name="description" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <!-- Slogan -->
                    <div>
                        <x-input-label for="slogan" value="Slogan" />
                        <x-text-input id="slogan" name="slogan" type="text" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('slogan')" />
                    </div>

                    <!-- Logo -->
                    <div>
                        <x-input-label for="logo" value="Logo" />
                        <input type="file" name="logo" id="logo" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-gray-100 file:text-gray-700
                                hover:file:bg-gray-200
                                dark:file:bg-gray-700 dark:file:text-gray-300
                                dark:hover:file:bg-gray-600">
                        <div id="logoPreview" class="mt-2 hidden">
                            <img id="logoPreviewImg" src="" alt="Preview"
                                class="h-20 w-20 rounded object-cover">
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                    </div>

                    <!-- Email Kontak -->
                    <div>
                        <x-input-label for="contact_email" value="Email Kontak" />
                        <x-text-input id="contact_email" name="contact_email" type="email"
                            class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                    </div>

                    <!-- Website -->
                    <div>
                        <x-input-label for="website" value="Website" />
                        <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('website')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button type="button" x-on:click="$dispatch('close')">
                        Batal
                    </x-secondary-button>
                    <x-primary-button>
                        Simpan
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Import Major Modal -->
    <x-modal name="import-major-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Import Jurusan via CSV') }}
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Pastikan CSV mengikuti struktur template. Duplikat nama atau kode jurusan akan dilewati.
            </p>

            <div class="mb-4">
                <a href="{{ route('admin.majors.template') }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                    Download Template
                </a>
            </div>

            <form method="POST" action="{{ route('admin.majors.import') }}" enctype="multipart/form-data"
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

    <script src="{{ asset('js/major-management.js') }}"></script>
</x-app-layout>
