<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Template Jadwal') }}
            </h2>
            <a href="{{ route('admin.schedules.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2" />
                Kembali ke Jadwal
            </a>
        </div>
    </x-slot>

    <div class="py-2 flex items-center justify-end ml-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ $activeTerm
                ? 'Semester ' . ucfirst($activeTerm->kind) . ' Tahun Ajaran ' . $activeTerm->tahun_ajaran
                : 'Tahun Ajaran Tidak Diketahui' }}
        </p>
    </div>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Alert Messages -->
            @if (session('success'))
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-check-circle
                            class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" />
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-heroicon-o-x-circle class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 flex-shrink-0" />
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Manajemen Template Jadwal
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola template jadwal per kelas dan block
                            </p>
                        </div>
                        <x-secondary-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'add-template-modal')">
                            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                            Buat Template Baru
                        </x-secondary-button>
                    </div>

                    <form method="GET" action="{{ route('admin.schedules.templates.index') }}"
                        class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                                Jurusan</label>
                            <select name="major_id" onchange="this.form.submit()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Jurusan</option>
                                @foreach ($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ $selectedMajorId == $major->id ? 'selected' : '' }}>
                                        {{ $major->code }} - {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter
                                Block</label>
                            <select name="block_id" onchange="this.form.submit()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Block</option>
                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}"
                                        {{ $selectedBlockId == $block->id ? 'selected' : '' }}>
                                        {{ $block->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <a href="{{ route('admin.schedules.templates.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                <x-heroicon-o-arrow-path class="w-4 h-4 mr-1" />
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Templates List -->
            <div class="space-y-4">
                @forelse ($classes as $class)
                    @php
                        $classTemplates = $templates->get($class->id) ?? collect();
                    @endphp
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                            onclick="toggleClass('{{ $class->id }}')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                            <x-heroicon-o-academic-cap
                                                class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $class->full_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $classTemplates->count() }} template
                                            @if ($classTemplates->where('is_active', true)->count() > 0)
                                                • <span class="text-green-600 dark:text-green-400">1 aktif</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <x-heroicon-o-chevron-down id="chevron-{{ $class->id }}"
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200" />
                            </div>
                        </div>

                        <!-- Templates Table -->
                        <div id="templates-{{ $class->id }}"
                            class="hidden border-t border-gray-200 dark:border-gray-700">
                            @if ($classTemplates->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Versi</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Block</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Status</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Entri</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($classTemplates as $template)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        Versi {{ $template->version }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $template->block->name ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        @if ($template->is_active)
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                                Aktif
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                                Nonaktif
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $template->entries->count() }} entri
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="flex items-center gap-2">
                                                            <!-- View Schedule -->
                                                            <a href="{{ route('admin.schedules.index', ['major_id' => $class->major_id, 'class_id' => $class->id, 'template_id' => $template->id]) }}"
                                                                class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                                                                title="Lihat Jadwal">
                                                                <x-heroicon-o-calendar class="w-5 h-5" />
                                                            </a>

                                                            @if (!$template->is_active)
                                                                <!-- Activate -->
                                                                <form method="POST"
                                                                    action="{{ route('admin.schedules.templates.activate', $template) }}"
                                                                    class="inline">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="p-1.5 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-md transition-colors"
                                                                        title="Aktifkan">
                                                                        <x-heroicon-o-check-circle class="w-5 h-5" />
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <!-- Deactivate -->
                                                                <form method="POST"
                                                                    action="{{ route('admin.schedules.templates.deactivate', $template) }}"
                                                                    class="inline">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="p-1.5 text-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 rounded-md transition-colors"
                                                                        title="Nonaktifkan">
                                                                        <x-heroicon-o-pause-circle class="w-5 h-5" />
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <!-- Delete -->
                                                            <button type="button"
                                                                onclick="deleteTemplate('{{ $template->id }}', 'Versi {{ $template->version }}')"
                                                                class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                                                                title="Hapus">
                                                                <x-heroicon-o-trash class="w-5 h-5" />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <x-heroicon-o-document class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                                    <p>Belum ada template untuk kelas ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <x-heroicon-o-academic-cap class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada kelas</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada kelas yang sesuai dengan filter
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add Template Modal -->
    <x-modal name="add-template-modal" focusable>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Buat Template Baru
                </h2>
                <button type="button" x-on:click="$dispatch('close')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form method="POST" action="{{ route('admin.schedules.templates.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="major_id" value="{{ $selectedMajorId }}">
                <input type="hidden" name="block_id" value="{{ $selectedBlockId }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas *</label>
                    <select name="class_id" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Pilih Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Block *</label>
                    <select name="block_id" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Pilih Block</option>
                        @foreach ($blocks as $block)
                            <option value="{{ $block->id }}"
                                {{ $selectedBlockId == $block->id ? 'selected' : '' }}>
                                {{ $block->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Template baru akan dibuat dengan status nonaktif. Anda dapat mengaktifkannya setelah mengisi jadwal.
                </p>

                <div
                    class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 -mx-6 -mb-6 mt-6">
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                        Buat Template
                    </button>
                    <button type="button" x-on:click="$dispatch('close')"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Template Modal -->
    <x-modal name="delete-template-modal" focusable>
        <div class="p-6">
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Hapus Template
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus template <span id="deleteTemplateName"
                            class="font-semibold"></span>?
                        Template hanya dapat dihapus jika tidak memiliki entri jadwal.
                    </p>
                </div>
            </div>

            <form id="deleteTemplateForm" method="POST">
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

    <script>
        function toggleClass(classId) {
            const content = document.getElementById('templates-' + classId);
            const chevron = document.getElementById('chevron-' + classId);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        function deleteTemplate(templateId, templateName) {
            const form = document.getElementById('deleteTemplateForm');
            form.action = `/admin/schedules/templates/${templateId}`;
            document.getElementById('deleteTemplateName').textContent = templateName;

            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'delete-template-modal'
            }));
        }
    </script>
</x-app-layout>
