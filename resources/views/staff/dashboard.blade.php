<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard Staff') }}
            </h2>
        </div>
    </x-slot>

        {{-- Alert absen pojok kanan bawah --}}
    @if ($isAbsensiActive && !$attendanceRecord)
        <div
            class="bg-white shadow-lg rounded-2xl overflow-hidden
                    border-2 border-blue-200 dark:border-blue-600 p-5 md:p-6
                    hover:shadow-xl transition-all duration-150 fixed right-0 bottom-0 w-full max-w-sm z-10 ring-2 ring-blue-100 dark:ring-blue-500/30">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1">
                    <p class="text-xs md:text-sm font-semibold text-blue-600 dark:text-blue-400 mb-2 uppercase tracking-wide">Absensi Hari Ini</p>
                        <h3 class="text-2xl font-extrabold text-red-600 dark:text-red-400 mb-2">Belum Absen</h3>
                        <a href="{{ route('staff.attendance.index') }}"
                            class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors duration-200">
                            Absen Sekarang
                            <x-heroicon-o-arrow-right class="w-4 h-4 inline-block ml-1" />
                        </a>
                </div>
                <div
                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br {{ $attendanceRecord ? 'from-green-100 to-green-50 dark:from-green-900/30 dark:to-green-800/20' : 'from-red-100 to-red-50 dark:from-red-900/30 dark:to-red-800/20' }} rounded-xl flex items-center justify-center
                            border-2 {{ $attendanceRecord ? 'border-green-300 dark:border-green-600' : 'border-red-300 dark:border-red-600' }} shadow-md">
                        <x-heroicon-o-exclamation-circle
                            class="w-8 h-8 text-red-600 dark:text-red-400" />
                </div>
            </div>
        </div>
    @endif

    <div class="py-8">
        <div>
            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <!-- Total Siswa -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                            <x-heroicon-o-users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded">+12%</span>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Siswa</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalStudents ?? 0) }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Aktif semester ini</p>
                </div>

                <!-- Total Guru -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                            <x-heroicon-o-academic-cap class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">+3</span>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Guru</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTeachers ?? 0) }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Tenaga pengajar aktif</p>
                </div>

                <!-- Total Kelas -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
                            <x-heroicon-o-building-office class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <span class="text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-2 py-1 rounded">{{ number_format($totalClasses ?? 0) }}</span>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Kelas</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalClasses ?? 0) }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Dari 12 jurusan</p>
                </div>

                <!-- Total Ruangan -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-amber-50 dark:bg-amber-900/20 p-3 rounded-lg">
                            <x-heroicon-o-home class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                        </div>
                        <span class="text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded">{{ number_format($totalRooms ?? 0) }}</span>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Ruangan</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalRooms ?? 0) }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Termasuk lab & praktik</p>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <!-- Jadwal Hari Ini -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                    <x-heroicon-o-calendar class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Hari Ini</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse(($todayEntries ?? []) as $entry)
                                <div class="flex items-start gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex-shrink-0 text-center">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $entry['start'] ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $entry['end'] ?? '' }}</div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $entry['subject'] ?? '—' }}</h4>
                                            @if(! empty($entry['ongoing']))
                                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">Berlangsung</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-o-user-circle class="w-3.5 h-3.5" />
                                                {{ $entry['teacher'] ?? '—' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-o-user-group class="w-3.5 h-3.5" />
                                                {{ $entry['class'] ?? '—' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-o-map-pin class="w-3.5 h-3.5" />
                                                {{ $entry['room'] ?? '—' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada jadwal untuk hari ini.</div>
                            @endforelse
                        </div>
                        <a href="#" class="mt-4 block w-full py-2 text-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors rounded">
                            Lihat Semua Jadwal
                        </a>
                    </div>
                </div>

                <!-- Quick Actions & Activity Log -->
                <div class="space-y-6">

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <button class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left">
                                <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                    <x-heroicon-o-user-plus class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Siswa</span>
                            </button>
                            <button class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left">
                                <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg">
                                    <x-heroicon-o-user-plus class="w-4 h-4 text-green-600 dark:text-green-400" />
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Guru</span>
                            </button>
                            <button class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left">
                                <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg">
                                    <x-heroicon-o-calendar-days class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Atur Jadwal</span>
                            </button>
                            <button class="w-full flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left">
                                <div class="bg-amber-100 dark:bg-amber-900/30 p-2 rounded-lg">
                                    <x-heroicon-o-building-office class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Kelola Kelas</span>
                            </button>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
                        <div class="space-y-4">
                            @forelse(($recentActivities ?? []) as $activity)
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full"
                                        @if(str_contains($activity['message'], 'ditambahkan')) style="background-color: #22c55e;" @elseif(str_contains($activity['message'], 'diperbarui')) style="background-color: #3b82f6;" @elseif(str_contains($activity['message'], 'Kelas')) style="background-color: #a855f7;" @else style="background-color: #f59e0b;" @endif>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $activity['message'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $activity['time']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada aktivitas terbaru</div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

            <!-- Additional Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Mata Pelajaran -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-3 rounded-lg">
                            <x-heroicon-o-book-open class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Mata Pelajaran</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($totalSubjects ?? 0) }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span>Total mata pelajaran</span>
                    </div>
                </div>

                <!-- Semester Aktif -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 p-3 rounded-lg">
                            <x-heroicon-o-calendar-days class="w-6 h-6 text-cyan-600 dark:text-cyan-400" />
                        </div>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Semester Aktif</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $termLabel ?? 'Tidak ada semester aktif' }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ $termPeriod ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


