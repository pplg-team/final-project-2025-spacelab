<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Detail Kelas') }}
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ redirect()->back()->getTargetUrl() }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                bg-gradient-to-r from-gray-800 to-gray-700">
            <x-heroicon-o-arrow-left class="w-5 h-5 text-gray-100" />
            <span class="text-sm font-medium text-gray-100">Kembali</span>
        </a>
    </div>
    <div class="py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Messages -->
            @if (session('success'))
                <div id="successAlert"
                        class="mb-6 bg-gray-50 dark:bg-gray-900/50 border-l-4 border-gray-900 dark:border-gray-100 p-4 rounded-r">
                        <div class="flex items-center">
                            <x-heroicon-o-check-circle
                                class="w-5 h-5 text-gray-900 dark:text-gray-100 mr-3 flex-shrink-0" />
                            <p class="text-sm font-normal text-gray-900 dark:text-gray-100">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div id="errorAlert"
                        class="mb-6 bg-gray-50 dark:bg-gray-900/50 border-l-4 border-gray-900 dark:border-gray-100 p-4 rounded-r">
                        <div class="flex items-center">
                            <x-heroicon-o-exclamation-circle
                                class="w-5 h-5 text-gray-900 dark:text-gray-100 mr-3 flex-shrink-0" />
                            <p class="text-sm font-normal text-gray-900 dark:text-gray-100">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div id="infoAlert"
                        class="mb-6 bg-gray-50 dark:bg-gray-900/50 border-l-4 border-gray-900 dark:border-gray-100 p-4 rounded-r">
                        <div class="flex items-center">
                            <x-heroicon-o-information-circle
                                class="w-5 h-5 text-gray-900 dark:text-gray-100 mr-3 flex-shrink-0" />
                            <p class="text-sm font-normal text-gray-900 dark:text-gray-100">{{ session('info') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Header Section -->
                <div class="mb-8 pb-6 sm:pb-8 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="space-y-2">
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $classroom->full_name }}</h1>
                            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                {{ $classroom->major->name }} · {{ $classroom->major->code }}</p>
                        </div>
                        <div class="flex items-center gap-4 sm:gap-6">
                            <div class="text-center sm:text-right">
                                <div
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                    Tingkat</div>
                                <div class="text-gray-900 dark:text-gray-100 text-base sm:text-lg font-semibold">
                                    {{ $classroom->level }}</div>
                            </div>
                            <div class="text-center sm:text-right">
                                <div
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                    Rombel</div>
                                <div class="text-gray-900 dark:text-gray-100 text-base sm:text-lg font-semibold">
                                    {{ $classroom->rombel }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">

                            {{ $activeTerm
                                ? 'Semester ' . ucfirst($activeTerm->kind) . ' Tahun Ajaran ' . $activeTerm->tahun_ajaran
                                : 'Tahun Ajaran Tidak Diketahui'
                            }}

                        </span>
                    </p>
                </div>

                <!-- Guardian Section -->
                <div class="mb-10 sm:mb-12">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3
                            class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                            Wali Kelas</h3>
                        <button onclick="openGuardianModal()"
                            class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                            {{ $guardian ? 'Ubah' : 'Tentukan' }}
                        </button>
                    </div>

                    @if ($guardian)
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 sm:p-6 bg-gray-50 dark:bg-gray-900/30 rounded-lg">
                            @if ($guardian->teacher->avatar)
                                <img src="{{ Storage::url($guardian->teacher->avatar) }}" alt="{{ $guardian->teacher->user->name }}"
                                    class="h-14 w-14 sm:h-16 sm:w-16 object-cover rounded-lg flex-shrink-0">
                            @else
                                <div
                                    class="h-14 w-14 sm:h-16 sm:w-16 bg-gray-200 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 text-lg font-medium rounded-lg flex-shrink-0">
                                    {{ substr($guardian->teacher->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $guardian->teacher->user->name }}</div>
                                <div class="text-xs sm:text-sm font-normal text-gray-500 dark:text-gray-400">NIP:
                                    {{ $guardian->teacher->nip ?? '-' }}</div>
                                <div class="text-xs font-normal text-gray-400 dark:text-gray-500 mt-2">Sejak
                                    {{ $guardian->started_at->translatedFormat('d M Y') }}</div>
                            </div>
                        </div>
                    @else
                        <div class="p-8 sm:p-12 bg-gray-50 dark:bg-gray-900/30 text-center rounded-lg">
                            <p class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-3">Belum ditentukan</p>
                            <button onclick="openGuardianModal()"
                                class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                                Tentukan Wali Kelas →
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Students Section -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 sm:mb-6">
                        <div class="flex items-baseline gap-3">
                            <h3
                                class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                Daftar Siswa</h3>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500">{{ $students->count() }}
                                terdaftar</span>
                        </div>
                        <button onclick="openStudentModal()"
                            class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors self-start sm:self-auto">
                            Tambah Siswa +
                        </button>
                    </div>

                    @if ($students->count() > 0)
                        <div class="space-y-2 sm:space-y-1 bg-gray-100 dark:bg-gray-900/30 rounded-lg overflow-hidden">
                            @foreach ($students as $index => $student)
                                <div
                                    class="bg-white dark:bg-gray-900 p-4 sm:p-6 group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <!-- Mobile Layout -->
                                    <div class="flex sm:hidden flex-col gap-3">
                                        <div class="flex items-start gap-3">
                                            <div class="text-xs font-medium text-gray-400 dark:text-gray-500 mt-1">
                                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                            @if ($student->avatar)
                                                <img class="h-12 w-12 object-cover rounded-lg flex-shrink-0"
                                                    src="{{ Storage::url($student->avatar) }}" alt="">
                                            @else
                                                <div
                                                    class="h-12 w-12 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 text-sm font-medium rounded-lg flex-shrink-0">
                                                    {{ substr($student->user->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                    {{ $student->user->name }}</div>
                                                <div
                                                    class="text-xs font-normal text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $student->user->email }}</div>
                                            </div>
                                            <button
                                                onclick="deleteStudent('{{ $student->id }}', '{{ $student->user->name }}')"
                                                class="text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 flex-shrink-0"
                                                title="Hapus">
                                                <x-heroicon-o-trash class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 pl-16">
                                            <div>
                                                <div class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-1">
                                                    NIS</div>
                                                <div class="text-sm font-normal text-gray-900 dark:text-gray-100">
                                                    {{ $student->nis ?? '-' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-1">
                                                    NISN</div>
                                                <div class="text-sm font-normal text-gray-900 dark:text-gray-100">
                                                    {{ $student->nisn ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desktop Layout -->
                                    <div class="hidden sm:flex items-center gap-4 lg:gap-6">
                                        <div class="w-8 text-xs font-medium text-gray-400 dark:text-gray-500">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>

                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            @if ($student->avatar)
                                                <img class="h-12 w-12 object-cover rounded-lg flex-shrink-0"
                                                    src="{{ Storage::url($student->avatar) }}" alt="">
                                            @else
                                                <div
                                                    class="h-12 w-12 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 text-sm font-medium rounded-lg flex-shrink-0">
                                                    {{ substr($student->user->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                    {{ $student->user->name }}</div>
                                                <div
                                                    class="text-xs font-normal text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $student->user->email }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-6 lg:gap-8">
                                            <div class="text-right">
                                                <div class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-1">
                                                    NIS</div>
                                                <div class="text-sm font-normal text-gray-900 dark:text-gray-100">
                                                    {{ $student->nis ?? '-' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs font-medium text-gray-400 dark:text-gray-500 mb-1">
                                                    NISN</div>
                                                <div class="text-sm font-normal text-gray-900 dark:text-gray-100">
                                                    {{ $student->nisn ?? '-' }}</div>
                                            </div>
                                            <button
                                                onclick="deleteStudent('{{ $student->id }}', '{{ $student->user->name }}')"
                                                class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                                                title="Hapus">
                                                <x-heroicon-o-trash class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 sm:py-24 bg-gray-50 dark:bg-gray-900/30 rounded-lg">
                            <div class="mb-4">
                                <x-heroicon-o-users
                                    class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 dark:text-gray-700 mx-auto" />
                            </div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Belum ada siswa
                                terdaftar</p>
                            <p class="text-xs font-normal text-gray-400 dark:text-gray-500 mb-6">Mulai tambahkan siswa
                                ke kelas ini</p>
                            <button onclick="openStudentModal()"
                                class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                                Tambah Siswa +
                            </button>
                        </div>
                    @endif
                </div>

            </div>
    </div>

    <!-- Guardian Modal -->
    <x-modal name="guardianModal" :show="false" focusable>
        <div class="p-6 sm:p-8">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Pilih Wali Kelas</h2>
            <form method="POST" action="{{ route('admin.classrooms.guardian.update', $classroom->id) }}">
                @csrf
                <div class="mb-6">
                    <label for="teacher_id"
                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">Guru</label>
                    <select id="teacher_id" name="teacher_id"
                        class="block w-full text-sm font-normal border-0 border-b-2 border-gray-200 dark:border-gray-700 bg-transparent dark:text-gray-300 focus:border-gray-900 dark:focus:border-gray-100 focus:ring-0 py-2 px-0"
                        required>
                        <option value="">Pilih guru</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ $guardian && $guardian->teacher_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-gray-900 dark:bg-gray-100 dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Add Student Modal -->
    <x-modal name="studentModal" :show="false" focusable>
        <div class="p-6 sm:p-8">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Tambah Siswa</h2>

            @if ($availableStudents->isEmpty())
                <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900/30 mb-6 rounded-lg">
                    <p class="text-xs sm:text-sm font-normal text-gray-600 dark:text-gray-400">
                        Tidak ada siswa yang tersedia untuk ditambahkan saat ini.
                    </p>
                </div>
                <div class="flex justify-end">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                        Tutup
                    </button>
                </div>
            @else
                <form method="POST" action="{{ route('admin.classrooms.students.store', $classroom->id) }}">
                    @csrf
                    <div class="mb-6">
                        <label for="student_id"
                            class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">Siswa</label>
                        <select id="student_id" name="student_id"
                            class="block w-full text-sm font-normal border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-gray-300 focus:border-gray-900 dark:focus:border-gray-100 focus:ring-0 py-2 px-3 rounded"
                            required size="8">
                            @foreach ($availableStudents as $student)
                                <option value="{{ $student->id }}" class="py-2">
                                    {{ $student->user->name }} ({{ $student->nis ?? 'No NIS' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-3 text-xs font-normal text-gray-400 dark:text-gray-500">Tekan Ctrl+F untuk mencari
                            nama siswa</p>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-gray-900 dark:bg-gray-100 dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors rounded">
                            Tambahkan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </x-modal>

    <!-- Delete Form -->
    <form id="deleteStudentForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openGuardianModal() {
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'guardianModal'
            }));
        }

        function openStudentModal() {
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'studentModal'
            }));
        }

        function deleteStudent(studentId, studentName) {
            if (confirm('Apakah Anda yakin ingin menghapus siswa "' + studentName + '" dari kelas ini?')) {
                const form = document.getElementById('deleteStudentForm');
                form.action = "{{ route('admin.classrooms.students.destroy', [$classroom->id, ':studentId']) }}".replace(
                    ':studentId', studentId);
                form.submit();
            }
        }
    </script>
</x-app-layout>
