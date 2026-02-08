<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pusat Laporan') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $activeTerm ? 'Semester ' . ucfirst($activeTerm->kind) . ' - ' . $activeTerm->tahun_ajaran : 'Tahun Ajaran Tidak Aktif' }}
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Total Students -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Siswa</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($stats['total_students']) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-xl">
                            <x-heroicon-o-academic-cap class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <!-- Total Teachers -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Guru</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($stats['total_teachers']) }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl">
                            <x-heroicon-o-user-group class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                    </div>
                </div>

                <!-- Total Classes -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Kelas</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($stats['total_classes']) }}</p>
                        </div>
                        <div class="p-3 bg-violet-100 dark:bg-violet-900/50 rounded-xl">
                            <x-heroicon-o-rectangle-group class="w-6 h-6 text-violet-600 dark:text-violet-400" />
                        </div>
                    </div>
                </div>

                <!-- Total Majors -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Jurusan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($stats['total_majors']) }}</p>
                        </div>
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/50 rounded-xl">
                            <x-heroicon-o-building-library class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                        </div>
                    </div>
                </div>

                <!-- Total Rooms -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Ruangan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($stats['total_rooms']) }}</p>
                        </div>
                        <div class="p-3 bg-rose-100 dark:bg-rose-900/50 rounded-xl">
                            <x-heroicon-o-home-modern class="w-6 h-6 text-rose-600 dark:text-rose-400" />
                        </div>
                    </div>
                </div>

                <!-- Total Subjects -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Mata Pelajaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($stats['total_subjects']) }}</p>
                        </div>
                        <div class="p-3 bg-cyan-100 dark:bg-cyan-900/50 rounded-xl">
                            <x-heroicon-o-book-open class="w-6 h-6 text-cyan-600 dark:text-cyan-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Categories -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pilih Jenis Laporan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Klik pada jenis laporan untuk melihat
                        detail dan mengunduh data</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Student Report Card -->
                        <a href="{{ route('staff.reports.students') }}"
                            class="group relative bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl p-6 border border-blue-200 dark:border-blue-800 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <x-heroicon-s-academic-cap class="w-16 h-16 text-blue-600" />
                            </div>
                            <div class="relative">
                                <div class="p-3 bg-blue-500 dark:bg-blue-600 rounded-xl w-fit mb-4">
                                    <x-heroicon-o-academic-cap class="w-6 h-6 text-white" />
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Siswa</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Data siswa per kelas dengan export
                                    CSV</p>
                                <div
                                    class="mt-4 flex items-center text-blue-600 dark:text-blue-400 text-sm font-medium">
                                    <span>Lihat Laporan</span>
                                    <x-heroicon-o-arrow-right
                                        class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </a>

                        <!-- Teacher Report Card -->
                        <a href="{{ route('staff.reports.teachers') }}"
                            class="group relative bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-xl p-6 border border-emerald-200 dark:border-emerald-800 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <x-heroicon-s-user-group class="w-16 h-16 text-emerald-600" />
                            </div>
                            <div class="relative">
                                <div class="p-3 bg-emerald-500 dark:bg-emerald-600 rounded-xl w-fit mb-4">
                                    <x-heroicon-o-user-group class="w-6 h-6 text-white" />
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Guru</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Data guru dan beban mengajar</p>
                                <div
                                    class="mt-4 flex items-center text-emerald-600 dark:text-emerald-400 text-sm font-medium">
                                    <span>Lihat Laporan</span>
                                    <x-heroicon-o-arrow-right
                                        class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </a>

                        <!-- Schedule Report Card -->
                        <a href="{{ route('staff.reports.schedules') }}"
                            class="group relative bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/30 dark:to-violet-800/30 rounded-xl p-6 border border-violet-200 dark:border-violet-800 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <x-heroicon-s-calendar-days class="w-16 h-16 text-violet-600" />
                            </div>
                            <div class="relative">
                                <div class="p-3 bg-violet-500 dark:bg-violet-600 rounded-xl w-fit mb-4">
                                    <x-heroicon-o-calendar-days class="w-6 h-6 text-white" />
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Jadwal</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jadwal pelajaran per kelas</p>
                                <div
                                    class="mt-4 flex items-center text-violet-600 dark:text-violet-400 text-sm font-medium">
                                    <span>Lihat Laporan</span>
                                    <x-heroicon-o-arrow-right
                                        class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </a>

                        <!-- Room Report Card -->
                        <a href="{{ route('staff.reports.rooms') }}"
                            class="group relative bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/30 dark:to-rose-800/30 rounded-xl p-6 border border-rose-200 dark:border-rose-800 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                            <div class="absolute top-4 right-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <x-heroicon-s-home-modern class="w-16 h-16 text-rose-600" />
                            </div>
                            <div class="relative">
                                <div class="p-3 bg-rose-500 dark:bg-rose-600 rounded-xl w-fit mb-4">
                                    <x-heroicon-o-home-modern class="w-6 h-6 text-white" />
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laporan Ruangan
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Penggunaan ruangan kelas</p>
                                <div
                                    class="mt-4 flex items-center text-rose-600 dark:text-rose-400 text-sm font-medium">
                                    <span>Lihat Laporan</span>
                                    <x-heroicon-o-arrow-right
                                        class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Distribution per Major -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribusi Siswa per Jurusan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan jumlah siswa berdasarkan jurusan
                        dan kelas</p>
                </div>

                <div class="p-6">
                    @if ($studentDistribution->count() > 0)
                        <div class="space-y-6">
                            @foreach ($studentDistribution as $majorCode => $classes)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <h4 class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                            <x-heroicon-o-building-library class="w-5 h-5 text-gray-400" />
                                            {{ $majorCode ?? 'Tidak Diketahui' }}
                                            <span class="ml-auto text-sm text-gray-500 dark:text-gray-400">
                                                {{ $classes->sum('class_histories_count') }} siswa
                                            </span>
                                        </h4>
                                    </div>
                                    <div class="p-4">
                                        <div
                                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                            @foreach ($classes->sortBy(['level', 'rombel']) as $class)
                                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 text-center">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $class->full_name }}</p>
                                                    <p
                                                        class="text-2xl font-bold text-gray-700 dark:text-gray-300 mt-1">
                                                        {{ $class->class_histories_count }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">siswa</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <x-heroicon-o-chart-bar class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada data</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada data distribusi siswa
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
