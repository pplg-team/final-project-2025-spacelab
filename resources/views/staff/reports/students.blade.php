<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('staff.reports.index') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-5 h-5 text-gray-600 dark:text-gray-400" />
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Laporan Siswa') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Data siswa per kelas</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filter Data</h3>
                    <form method="GET" action="{{ route('staff.reports.students') }}"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Major Filter -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
                            <select name="major_id" id="majorFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                onchange="this.form.submit()">
                                <option value="">Pilih Jurusan</option>
                                @foreach ($majors as $major)
                                    <option value="{{ $major->id }}"
                                        {{ $selectedMajorId == $major->id ? 'selected' : '' }}>
                                        {{ $major->code }} - {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Class Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                            <select name="class_id" id="classFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                onchange="this.form.submit()" {{ !$selectedMajorId ? 'disabled' : '' }}>
                                <option value="">Pilih Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-end gap-2">
                            <a href="{{ route('staff.reports.students') }}"
                                class="inline-flex items-center px-4 py-2.5 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                <x-heroicon-o-arrow-path class="w-4 h-4 mr-2" />
                                Reset
                            </a>
                            @if ($selectedClassId)
                                <a href="{{ route('staff.reports.students.export', ['class_id' => $selectedClassId]) }}"
                                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 transition">
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                                    Export CSV
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Students Table -->
            @if ($selectedClassId && $students->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Daftar Siswa Kelas {{ $selectedClass->full_name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Total: {{ $students->count() }} siswa
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                    {{ $selectedClass->major->name ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        NIS
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        NISN
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Email
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($students as $index => $student)
                                    <tr class="dark:hover:bg-gray-900/50 transition-colors">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                                            {{ $student->nis ?? '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                                            {{ $student->nisn ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($student->avatar)
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="{{ Storage::url($student->avatar) }}" alt="">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                            <span
                                                                class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $student->user->name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $student->user->email ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Menampilkan {{ $students->count() }} siswa
                            </p>
                            <a href="{{ route('staff.reports.students.export', ['class_id' => $selectedClassId]) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 transition">
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2" />
                                Download CSV
                            </a>
                        </div>
                    </div>
                </div>
            @elseif($selectedClassId && $students->count() == 0)
                <!-- No Students Found -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <x-heroicon-o-user-group class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada siswa</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Belum ada siswa terdaftar di kelas {{ $selectedClass->full_name }}
                        </p>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <x-heroicon-o-funnel class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Pilih Filter</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Pilih jurusan dan kelas untuk menampilkan data siswa
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
