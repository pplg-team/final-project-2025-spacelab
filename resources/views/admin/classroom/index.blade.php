<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Header Actions -->
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manajemen Kelas</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola kelas berdasarkan jurusan</p>
                </div>
                <div>
                    <x-secondary-button onclick="openClassroomModal()">
                        <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                        Tambah Kelas
                    </x-secondary-button>
                </div>
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

            <!-- Majors List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($majors as $major)
                            <div id="major-{{ $major->id }}"`
                                class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <!-- Major Header -->
                                <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <img src="{{ asset('storage/' . $major->logo) }}" alt=""
                                                class="w-12 h-12 object-cover rounded-full">
                                        </div>
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $major->name }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $major->code }}
                                                <span class="mx-2">•</span>
                                                {{ $major->classes->count() }} Kelas
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="toggleClassrooms('{{ $major->id }}')"
                                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                            <svg id="chevron-{{ $major->id }}"
                                                class="w-5 h-5 transform transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Classrooms Section (Collapsible) -->
                                <div id="classrooms-{{ $major->id }}"
                                    class="hidden border-t border-gray-200 dark:border-gray-700">
                                    <div class="px-6 py-4 bg-white dark:bg-gray-800">
                                        <div class="flex justify-between items-center mb-4">
                                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</h5>
                                            <div>
                                                <x-secondary-button
                                                    onclick="openClassroomModal('{{ $major->id }}')">
                                                    <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                                                    Tambah Kelas
                                                </x-secondary-button>
                                                <x-secondary-button type="button"
                                                    onclick="openImportClassroomModal('{{ $major->id }}')">
                                                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-2" />
                                                    Import CSV
                                                </x-secondary-button>
                                            </div>
                                        </div>

                                        @if ($major->classes->count() > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach ($major->classes as $classroom)
                                                    <div
                                                        class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <h6
                                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                    {{ $classroom->full_name }}
                                                                </h6>
                                                                <p
                                                                    class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                                    Level {{ $classroom->level }} - Rombel
                                                                    {{ $classroom->rombel }}
                                                                </p>
                                                            </div>
                                                            <div class="flex space-x-1">
                                                                <a href="{{ route('admin.classrooms.show', $classroom->id) }}"
                                                                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                                                    <x-heroicon-o-eye class="w-4 h-4" />
                                                                </a>
                                                                <button onclick="editClassroom('{{ $classroom->id }}')"
                                                                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                                                </button>
                                                                <button
                                                                    onclick="deleteClassroom('{{ $classroom->id }}')"
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
                                                Belum ada kelas yang ditambahkan
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <x-heroicon-o-ellipsis-horizontal class="w-12 h-12 text-gray-400 mx-auto" />
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada jurusan
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan jurusan terlebih
                                    dahulu
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classroom Modal -->
    <x-modal name="classroomModal" :show="false" focusable>
        <form id="classroomForm" method="POST" action="{{ route('admin.classrooms.store') }}">
            @csrf
            <input type="hidden" name="_method" id="classroomMethod" value="POST">
            <input type="hidden" name="classroom_id" id="classroomId">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="classroomModalTitle" class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Tambah Kelas
                    </h3>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
                        <x-select-input name="major_id" id="classroomMajorId" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}">{{ $major->name }} ({{ $major->code }})
                                </option>
                            @endforeach
                        </x-select-input>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Level</label>
                            <x-select-input name="level" id="classroomLevel" required>
                                <option value="">Pilih Level</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </x-select-input>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rombel</label>
                            <x-select-input name="rombel" id="classroomRombel" required>
                                <option value="">Pilih Rombel</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </x-select-input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="submit" id="classroomSubmitBtn"
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

    {{-- Import Modal --}}

    <!-- Import Major Modal -->
    <x-modal name="import-classroom-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Import Kelas via CSV') }}
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Pastikan CSV mengikuti struktur template. Duplikat kelas atau kode jurusan akan dilewati.
            </p>

            <div class="mb-4">
                <a href="{{ route('admin.classrooms.template') }}" target="_blank" id="importTemplateLink"
                    class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                    Download Template
                </a>
            </div>

            <form method="POST" action="{{ route('admin.classrooms.import') }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <input type="hidden" name="major_id" id="importClassroomMajorId">
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

    <script>
        // Configure routes for JavaScript
        window.classroomRoutes = {
            base: '/admin/classroomsjson',
            store: '{{ route('admin.classrooms.store') }}',
            delete: '/admin/classrooms'
        };
        window.csrfToken = '@csrf';
    </script>
    <script src="{{ asset('js/classroom-management.js') }}"></script>
    <script>
        // Inline function to guarantee availability
        window.openImportClassroomModal = function(majorId = null) {
            try {
                if (majorId) {
                    const input = document.getElementById('importClassroomMajorId');
                    if (input) input.value = majorId;

                    const templateLink = document.getElementById('importTemplateLink');
                    if (templateLink) {
                        const url = new URL(templateLink.href);
                        url.searchParams.set('major_id', majorId);
                        templateLink.href = url.toString();
                    }
                }
            } catch (e) {
                console.error('Error in openImportClassroomModal:', e);
            }

            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'import-classroom-modal'
            }));
        }
    </script>
</x-app-layout>
