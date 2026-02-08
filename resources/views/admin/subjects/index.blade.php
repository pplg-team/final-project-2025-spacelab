<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto">

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Mata Pelajaran</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola kurikulum dan alokasi pengajar
                        </p>
                    </div>
                    <button onclick="openSubjectModal()"
                        class="inline-flex items-center px-4 py-2.5 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Mata Pelajaran
                    </button>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div
                    class="mb-6 bg-gray-50 dark:bg-gray-800 border-l-4 border-gray-900 dark:border-gray-100 rounded-r-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-900 dark:text-gray-100 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-600 dark:border-red-400 rounded-r-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 mt-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex-1">
                            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.subjects.index') }}">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan kode atau nama mata pelajaran..."
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100 focus:border-transparent transition-all duration-200">
                    </div>
                </form>
            </div>

            <!-- Subjects Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @forelse ($subjects as $subject)
                    @if ($loop->first)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Mata Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Jurusan Diizinkan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Guru</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @endif
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $subject->code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $subject->name }}</p>
                                            @if ($subject->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($subject->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                            {{ ucfirst($subject->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($subject->allowedMajors->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($subject->allowedMajors->take(3) as $allowed)
                                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                                                        {{ $allowed->major->code }}
                                                    </span>
                                                @endforeach
                                                @if ($subject->allowedMajors->count() > 3)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">+{{ $subject->allowedMajors->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($subject->teachers->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($subject->teachers->take(2) as $teacher)
                                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">
                                                        {{ Str::limit($teacher->user->name, 20) }}
                                                    </span>
                                                @endforeach
                                                @if ($subject->teachers->count() > 2)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">+{{ $subject->teachers->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xl font-medium">
                                        <div class="flex justify-end gap-4">
                                            <button onclick='openSubjectModal(@json($subject))'
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors"
                                                title="Edit">
                                                <x-heroicon-o-pencil-square class="w-6 h-6" />
                                            </button>
                                            <button onclick="deleteSubject('{{ $subject->id }}')"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors"
                                                title="Hapus">
                                                <x-heroicon-o-trash class="w-6 h-6" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                    @if ($loop->last)
                            </tbody>
                        </table>
                    </div>
                    @endif
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data mata pelajaran</p>
                        <button onclick="openSubjectModal()"
                            class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah mata pelajaran pertama
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($subjects->hasPages())
                <div class="mt-6">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Subject Modal -->
    <x-modal name="subjectModal" :show="false" focusable>
        <form id="subjectForm" method="POST" action="{{ route('admin.subjects.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="subjectMethod" value="POST">

            <h2 id="subjectModalTitle" class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                Tambah Mata Pelajaran
            </h2>

            <div class="space-y-5">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Kode Mata Pelajaran
                    </label>
                    <input id="code" name="code" type="text" required
                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100 focus:border-transparent transition-all duration-200" />
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Mata Pelajaran
                    </label>
                    <input id="name" name="name" type="text" required
                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100 focus:border-transparent transition-all duration-200" />
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tipe
                    </label>
                    <div class="relative">
                        <select id="type" name="type" required
                            class="block w-full appearance-none px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100 focus:border-transparent transition-all duration-200">
                            <option value="teori">Teori</option>
                            <option value="praktikum">Praktikum</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="allowedMajors" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Jurusan yang Diizinkan <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3 space-y-2 bg-gray-50 dark:bg-gray-700">
                        @forelse ($majors as $major)
                            <label class="flex items-center cursor-pointer hover:bg-white dark:hover:bg-gray-800 p-2 rounded transition-colors">
                                <input type="checkbox" name="allowed_majors[]" value="{{ $major->id }}"
                                    class="w-4 h-4 text-gray-900 dark:text-gray-100 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100 dark:bg-gray-600 dark:border-gray-500" />
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $major->name }} <span class="text-gray-400">({{ $major->code }})</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada jurusan</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <textarea id="description" name="description" rows="3"
                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100 focus:border-transparent transition-all duration-200"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white dark:text-gray-900 bg-gray-900 dark:bg-gray-100 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Form -->
    <form id="deleteSubjectForm" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openSubjectModal(subject = null) {
            const modalTitle = document.getElementById('subjectModalTitle');
            const form = document.getElementById('subjectForm');
            const methodInput = document.getElementById('subjectMethod');
            const codeInput = document.getElementById('code');
            const nameInput = document.getElementById('name');
            const typeInput = document.getElementById('type');
            const descInput = document.getElementById('description');

            // Reset all allowed_majors checkboxes
            document.querySelectorAll('input[name="allowed_majors[]"]').forEach(el => el.checked = false);

            if (subject) {
                modalTitle.innerText = 'Edit Mata Pelajaran';
                form.action = `/admin/subjects/${subject.id}`;
                methodInput.value = 'PUT';
                codeInput.value = subject.code || '';
                nameInput.value = subject.name || '';
                typeInput.value = subject.type || '';
                descInput.value = subject.description || '';

                // Support multiple JSON shapes: allowedMajors (camelCase) or allowed_majors (snake_case)
                const allowedList = subject.allowedMajors || subject.allowed_majors || [];
                allowedList.forEach(allowed => {
                    // allowed may include major_id or nested major object
                    const majorId = allowed.major_id ?? (allowed.major && allowed.major.id) ?? allowed.id ?? null;
                    if (majorId) {
                        const checkbox = document.querySelector(`input[name="allowed_majors[]"][value="${majorId}"]`);
                        if (checkbox) checkbox.checked = true;
                    }
                });
            } else {
                modalTitle.innerText = 'Tambah Mata Pelajaran';
                form.action = "{{ route('admin.subjects.store') }}";
                methodInput.value = 'POST';
                codeInput.value = '';
                nameInput.value = '';
                typeInput.value = '';
                descInput.value = '';
            }

            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'subjectModal'
            }));
        }

        function deleteSubject(id) {
            if (!id) return;
            if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini? Tindakan ini tidak dapat dibatalkan.')) {
                const form = document.getElementById('deleteSubjectForm');
                form.action = `/admin/subjects/${id}`;
                form.submit();
            }
        }
    </script>
</x-app-layout>
