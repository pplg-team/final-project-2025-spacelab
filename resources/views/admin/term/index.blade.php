<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tahun Ajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="space-y-6">
            <!-- Header Actions -->
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manajemen Tahun Ajaran</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola tahun ajaran dan block semester</p>
                </div>
                <x-secondary-button onclick="openTermModal()">
                    <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                    Tambah Tahun Ajaran
                </x-secondary-button>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div id="successAlert"
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" />
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div id="errorAlert"
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-x-circle class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" />
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div id="validationAlert"
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 mt-0.5" />
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

            <!-- Terms List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($terms as $term)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <!-- Term Header -->
                                <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $term->tahun_ajaran }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $term->start_date->format('d M Y') }} -
                                                {{ $term->end_date->format('d M Y') }}
                                                <span class="mx-2">•</span>
                                                <span class="capitalize">{{ $term->kind }}</span>
                                            </p>
                                        </div>
                                        @if ($term->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                Aktif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="toggleBlocks('{{ $term->id }}')"
                                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                            <svg id="chevron-{{ $term->id }}"
                                                class="w-5 h-5 transform transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <button onclick="editTerm('{{ $term->id }}')"
                                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteTerm('{{ $term->id }}')"
                                            class="p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Blocks Section (Collapsible) -->
                                <div id="blocks-{{ $term->id }}"
                                    class="hidden border-t border-gray-200 dark:border-gray-700">
                                    <div class="px-6 py-4 bg-white dark:bg-gray-800">
                                        <div class="flex justify-between items-center mb-4">
                                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Block</h5>
                                            <button onclick="openBlockModal('{{ $term->id }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                <x-heroicon-o-plus class="w-3 h-3 mr-1" />
                                                Tambah Block
                                            </button>
                                        </div>

                                        @if ($term->blocks->count() > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach ($term->blocks as $block)
                                                    <div
                                                        class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <h6
                                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                    {{ $block->name }}</h6>
                                                                <p
                                                                    class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                                    {{ $block->start_date->format('d M') }} -
                                                                    {{ $block->end_date->format('d M Y') }}
                                                                </p>
                                                            </div>
                                                            <div class="flex space-x-1">
                                                                <button onclick="editBlock('{{ $block->id }}')"
                                                                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                                                </button>
                                                                <button onclick="deleteBlock('{{ $block->id }}')"
                                                                    class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                                                Belum ada block yang ditambahkan
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada tahun
                                    ajaran</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan tahun
                                    ajaran baru</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Term Modal -->
    <x-modal name="termModal" :show="false" focusable>
        <form id="termForm" method="POST" action="{{ route('admin.terms.store') }}">
            @csrf
            <input type="hidden" name="_method" id="termMethod" value="POST">
            <input type="hidden" name="term_id" id="termId">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="termModalTitle" class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Tambah Tahun Ajaran
                    </h3>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun
                            Ajaran</label>
                        <x-text-input name="tahun_ajaran" id="termTahunAjaran" required
                            placeholder="Contoh: 2024/2025" class="block w-full" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Mulai</label>
                            <x-date-input name="start_date" id="termStartDate" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Selesai</label>
                            <x-date-input name="end_date" id="termEndDate" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis</label>
                        <x-select-input name="kind" id="termKind" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </x-select-input>
                    </div>
                    <div class="flex items-center">
                        <x-checkbox-input name="is_active" id="is_active" value="1" />
                        <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Aktifkan
                            tahun
                            ajaran ini</label>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="submit" id="termSubmitBtn"
                    class="inline-flex w-full justify-center rounded-md bg-gray-800 dark:bg-gray-200 px-4 py-2 text-sm font-semibold text-white dark:text-gray-800 shadow-sm hover:bg-gray-700 dark:hover:bg-gray-300 sm:ml-3 sm:w-auto">
                    Simpan
                </button>
                <button type="button" x-on:click="$dispatch('close')"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                    Batal
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Block Modal -->
    <x-modal name="blockModal" :show="false" focusable>
        <form id="blockForm" method="POST" action="{{ route('admin.blocks.store') }}">
            @csrf
            <input type="hidden" name="_method" id="blockMethod" value="POST">
            <input type="hidden" name="terms_id" id="block_terms_id">
            <input type="hidden" name="block_id" id="blockId">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="blockModalTitle"
                        class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                        Tambah Block
                    </h3>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                            Block</label>
                        <x-text-input name="name" id="blockName" required placeholder="Contoh: Semester 1"
                            class="block w-full" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Mulai</label>
                            <x-date-input name="start_date" id="blockStartDate" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Selesai</label>
                            <x-date-input name="end_date" id="blockEndDate" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
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
    </x-modal>

    <script>
        // Configure routes for JavaScript
        window.termRoutes = {
            base: '/admin/terms',
            store: '{{ route('admin.terms.store') }}'
        };
        window.blockRoutes = {
            base: '/admin/blocks',
            store: '{{ route('admin.blocks.store') }}'
        };
        window.csrfToken = '@csrf';
    </script>
    <script src="{{ asset('js/term-management.js') }}"></script>
</x-app-layout>
