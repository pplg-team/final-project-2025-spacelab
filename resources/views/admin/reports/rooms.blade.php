<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.reports.index') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-5 h-5 text-gray-600 dark:text-gray-400" />
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Laporan Ruangan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Penggunaan ruangan kelas</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-rose-100 dark:bg-rose-900/50 rounded-xl">
                            <x-heroicon-o-home-modern class="w-6 h-6 text-rose-600 dark:text-rose-400" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Ruangan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rooms->count() }}</p>
                        </div>
                    </div>
                </div>

                @php
                    $highUsage = collect($roomUsage)->filter(fn($r) => $r['percentage'] >= 70)->count();
                    $mediumUsage = collect($roomUsage)
                        ->filter(fn($r) => $r['percentage'] >= 30 && $r['percentage'] < 70)
                        ->count();
                    $lowUsage = collect($roomUsage)->filter(fn($r) => $r['percentage'] < 30)->count();
                @endphp

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-red-100 dark:bg-red-900/50 rounded-xl">
                            <x-heroicon-o-fire class="w-6 h-6 text-red-600 dark:text-red-400" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tinggi (≥70%)</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $highUsage }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/50 rounded-xl">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sedang (30-70%)</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mediumUsage }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl">
                            <x-heroicon-o-check-circle class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Rendah (<30%)< /p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowUsage }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms Grid -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Penggunaan Ruangan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Berdasarkan jadwal aktif saat ini</p>
                </div>

                <div class="p-6">
                    @if ($rooms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($rooms as $room)
                                @php
                                    $usage = $roomUsage[$room->id] ?? ['count' => 0, 'percentage' => 0];
                                    $colorClass =
                                        $usage['percentage'] >= 70
                                            ? 'border-red-200 dark:border-red-800'
                                            : ($usage['percentage'] >= 30
                                                ? 'border-amber-200 dark:border-amber-800'
                                                : 'border-emerald-200 dark:border-emerald-800');
                                    $bgClass =
                                        $usage['percentage'] >= 70
                                            ? 'from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20'
                                            : ($usage['percentage'] >= 30
                                                ? 'from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20'
                                                : 'from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20');
                                    $barColor =
                                        $usage['percentage'] >= 70
                                            ? 'bg-red-500'
                                            : ($usage['percentage'] >= 30
                                                ? 'bg-amber-500'
                                                : 'bg-emerald-500');
                                    $textColor =
                                        $usage['percentage'] >= 70
                                            ? 'text-red-700 dark:text-red-300'
                                            : ($usage['percentage'] >= 30
                                                ? 'text-amber-700 dark:text-amber-300'
                                                : 'text-emerald-700 dark:text-emerald-300');
                                @endphp
                                <div
                                    class="bg-gradient-to-br {{ $bgClass }} rounded-xl p-5 border {{ $colorClass }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $room->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $room->building->name ?? 'Gedung Tidak Diketahui' }}
                                                @if ($room->floor)
                                                    • Lantai {{ $room->floor }}
                                                @endif
                                            </p>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-mono font-medium bg-white/50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300">
                                            {{ $room->code ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <span class="text-gray-600 dark:text-gray-400">Penggunaan</span>
                                            <span class="font-semibold {{ $textColor }}">{{ $usage['count'] }}
                                                jadwal</span>
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                            <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500"
                                                style="width: {{ $usage['percentage'] }}%"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-xs mt-1">
                                            <span class="text-gray-500 dark:text-gray-400">0%</span>
                                            <span
                                                class="font-medium {{ $textColor }}">{{ $usage['percentage'] }}%</span>
                                            <span class="text-gray-500 dark:text-gray-400">100%</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        @if ($room->capacity)
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-o-user-group class="w-3.5 h-3.5" />
                                                {{ $room->capacity }} orang
                                            </span>
                                        @endif
                                        @if ($room->type)
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-o-tag class="w-3.5 h-3.5" />
                                                {{ ucfirst($room->type) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <x-heroicon-o-home-modern class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada data</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada ruangan terdaftar</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Legend -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-red-500"></div>
                        <span class="text-gray-600 dark:text-gray-400">Tinggi (≥70%) - Perlu perhatian</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-amber-500"></div>
                        <span class="text-gray-600 dark:text-gray-400">Sedang (30-70%) - Normal</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-emerald-500"></div>
                        <span class="text-gray-600 dark:text-gray-400">Rendah (<30%) - Tersedia</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
