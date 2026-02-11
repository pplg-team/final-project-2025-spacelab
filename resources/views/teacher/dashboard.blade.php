<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black dark:text-white leading-tight">
            Dashboard Guru
        </h2>
    </x-slot>

        {{-- Alert absen pojok kanan bawah --}}
    @if ($isAbsensiActive)
        <div
            class="bg-white shadow-lg rounded-2xl overflow-hidden
                    border-2 border-blue-200 dark:border-blue-600 p-5 md:p-6
                    hover:shadow-xl transition-all duration-150 fixed right-0 bottom-0 w-full max-w-sm z-10 ring-2 ring-blue-100 dark:ring-blue-500/30">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1">
                    <p class="text-xs md:text-sm font-semibold text-blue-600 dark:text-blue-400 mb-2 uppercase tracking-wide">Absensi Hari Ini</p>
                    @if ($attendanceRecord)
                        <h3 class="text-2xl font-extrabold text-green-600 dark:text-green-400 mb-1">
                            Sudah Absen</h3>
                        <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 font-medium">Status:
                            {{ ucfirst($attendanceRecord->status ?? 'hadir') }}</p>
                    @else
                        <h3 class="text-2xl font-extrabold text-red-600 dark:text-red-400 mb-2">Belum Absen</h3>
                        <a href="{{ route('siswa.attendance.index') }}"
                            class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors duration-200">
                            Absen Sekarang
                            <x-heroicon-o-arrow-right class="w-4 h-4 inline-block ml-1" />
                        </a>
                    @endif  
                </div>
                <div
                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br {{ $attendanceRecord ? 'from-green-100 to-green-50 dark:from-green-900/30 dark:to-green-800/20' : 'from-red-100 to-red-50 dark:from-red-900/30 dark:to-red-800/20' }} rounded-xl flex items-center justify-center
                            border-2 {{ $attendanceRecord ? 'border-green-300 dark:border-green-600' : 'border-red-300 dark:border-red-600' }} shadow-md">
                    @if ($attendanceRecord)
                        <x-heroicon-o-check-circle
                            class="w-8 h-8 text-green-600 dark:text-green-400" />
                    @else
                        <x-heroicon-o-exclamation-circle
                            class="w-8 h-8 text-red-600 dark:text-red-400" />
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="py-10">
        <div>

            {{-- Statistik Ringkas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">

                <!-- Card 1: Pelajaran Hari Ini -->
                <article role="article" aria-label="Pelajaran Hari Ini"
                    class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Pelajaran Hari Ini</p>
                            <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white">
                                {{ $lessonsCount ?? 0 }}</h3>
                            <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">
                                {{ $uniqueSubjectsCount ?? 0 }} mata pelajaran • {{ $lessonsCount ?? 0 }} jadwal</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                            <span class="sr-only">Icon</span>
                            <x-heroicon-o-book-open class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                                aria-hidden="true" />
                        </div>
                    </div>
                </article>

                <!-- Card 2: Hari Ini -->
                <article role="article" aria-label="Hari Ini"
                    class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Hari Ini</p>
                            <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white capitalize">
                                {{ $currentTime->translatedFormat('l') ?? $currentTime->format('l') }}</h3>
                            <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">
                                {{ $currentTime->format('d M Y') }}</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                            <span class="sr-only">Icon</span>
                            <x-heroicon-o-calendar class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                                aria-hidden="true" />
                        </div>
                    </div>
                </article>

                <!-- Card 3: Jumlah Ruangan -->
                <article role="article" aria-label="Jumlah Ruangan"
                    class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Jumlah ruangan</p>
                            <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white">
                                {{ $roomsCount ?? 0 }}</h3>
                            <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">ruangan hari ini</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                            <span class="sr-only">Icon</span>
                            <x-heroicon-o-building-office-2
                                class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100" aria-hidden="true" />
                        </div>
                    </div>
                </article>

                <!-- Card 4: Nama Guru -->
                <article role="article" aria-label="Nama Guru"
                    class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Nama</p>
                            <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white truncate">
                                {{ optional(optional($teacher)->user)->name ?? (optional($teacher)->name ?? 'Nama Guru') }}
                            </h3>
                            <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">
                                {{ optional($teacher)->code ?? 'Guru' }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="bg-gray-50 dark:bg-gray-800 p-1 md:p-2 rounded-full border border-gray-100 dark:border-gray-700">
                                <img src="{{ Storage::url(optional($teacher)->avatar) ?? asset('images/default-teacher.png') }}"
                                    alt="Avatar Guru"
                                    class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover shadow" />
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div
                class="bg-white dark:bg-gray-900 shadow rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 my-5">
                <div class="bg-gray-100 dark:bg-gray-800 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm p-2 rounded-lg">
                                <x-heroicon-o-clock class="w-6 h-6 text-gray-600 dark:text-gray-100" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Jadwal Hari Ini</h3>
                                @if (!$schedulesToday->isEmpty())
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $schedulesToday->count() }}
                                        mata pelajaran</p>
                                @endif
                            </div>
                        </div>
                        @if (!$schedulesToday->isEmpty())
                            <div class="hidden sm:flex items-center gap-2 text-xs text-gray-600 dark:text-white/90">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 bg-blue-300 rounded-full animate-pulse"></span>
                                    <span class="hidden md:inline">Berlangsung</span>
                                </span>
                                <span class="text-gray-300 dark:text-gray-500">•</span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 bg-gray-300/40 rounded-full"></span>
                                    <span class="hidden md:inline">Selesai</span>
                                </span>
                                <span class="text-gray-300 dark:text-gray-500">•</span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 bg-white rounded-full"></span>
                                    <span class="hidden md:inline">Akan Datang</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    @if ($schedulesToday->isEmpty())
                        <div class="text-center py-16">
                            <div
                                class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full mb-5 shadow">
                                <span class="text-5xl">🎉</span>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">Tidak ada pelajaran
                                hari ini</h4>
                            <p class="text-gray-500 dark:text-gray-400">Nikmati hari liburmu dengan baik!</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @php
                                $currentTime = $currentTime ?? \Carbon\Carbon::now();
                                $currentDayIndex = $currentDayIndex ?? (int) $currentTime->format('N');
                            @endphp

                            @foreach ($schedulesToday as $schedule)
                                @php
                                    $isPeriodOnly = isset($schedule->is_period_only) && $schedule->is_period_only;
                                    $startTime = $schedule->period?->start_date;
                                    $endTime = $schedule->period?->end_date;
                                    $period = $schedule->period;
                                    $isOngoing =
                                        (int) $schedule->day_of_week === (int) $currentDayIndex &&
                                        ($schedule->isOngoing($currentTime) ?? false);
                                    $isPast =
                                        (int) $schedule->day_of_week === (int) $currentDayIndex &&
                                        ($schedule->isPast($currentTime) ?? false);
                                @endphp

                                @if ($isPeriodOnly)
                                    {{-- BREAK TIME CARD --}}
                                    <div class="relative group">
                                        @if ($isOngoing)
                                            <div
                                                class="absolute -left-6 top-0 bottom-0 w-1.5 bg-amber-400 rounded-full shadow animate-pulse">
                                            </div>
                                        @endif

                                        <div
                                            class="relative overflow-hidden rounded-xl border-2 transition-all duration-300
                                            {{ $isOngoing
                                                ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-300 dark:border-amber-600 shadow scale-[1.01]'
                                                : ($isPast
                                                    ? 'bg-gray-50 dark:bg-gray-800/30 border-gray-200 dark:border-gray-700 opacity-70'
                                                    : 'bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-800/50 dark:to-gray-800/30 border-slate-200 dark:border-gray-700 hover:shadow') }}
                                        ">
                                            @if ($isOngoing)
                                                <div
                                                    class="absolute top-0 right-0 bg-amber-500 text-white px-4 py-1.5 rounded-bl-xl shadow z-10">
                                                    <span class="text-xs font-bold tracking-wide">BERLANGSUNG</span>
                                                </div>
                                            @endif

                                            <div class="p-5">
                                                <div class="flex flex-col lg:flex-row gap-5 items-center">
                                                    {{-- Break Icon --}}
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="inline-flex items-center justify-center rounded-xl p-4 bg-slate-200 dark:bg-slate-700 shadow">
                                                            <svg class="w-8 h-8 text-slate-600 dark:text-slate-300"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <div class="flex-1">
                                                        <h4
                                                            class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-3 text-center lg:text-left">
                                                            {{ $schedule->period?->ordinal ?? 'Istirahat' }}
                                                        </h4>

                                                        <div
                                                            class="flex items-center justify-center lg:justify-start gap-6">
                                                            <div class="text-center">
                                                                <div
                                                                    class="text-2xl font-bold text-slate-700 dark:text-slate-300">
                                                                    {{ $schedule->period?->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $schedule->period->start_time)->format('H:i') : '-' }}
                                                                </div>
                                                            </div>

                                                            <div class="text-slate-400 dark:text-slate-500">—</div>

                                                            <div class="text-center">
                                                                <div
                                                                    class="text-2xl font-bold text-slate-700 dark:text-slate-300">
                                                                    {{ $schedule->period?->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $schedule->period->end_time)->format('H:i') : '-' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if ($schedule->period?->ordinal)
                                                            <div class="mt-3 text-center lg:text-left">
                                                                <span
                                                                    class="inline-block px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full text-xs font-medium">
                                                                    {{ $schedule->period->ordinal }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- CLASS SCHEDULE CARD --}}
                                    <div class="relative group">
                                        {{-- Ongoing Side Indicator --}}
                                        @if ($isOngoing)
                                            <div
                                                class="absolute -left-6 top-0 bottom-0 w-1.5 bg-blue-300 rounded-full shadow animate-pulse">
                                            </div>
                                        @endif

                                        <div
                                            class="
                                            relative overflow-hidden rounded-xl border-2 transition-all duration-300
                                            {{ $isOngoing
                                                ? 'bg-blue-50 dark:bg-gray-900/10 border-blue-300 dark:border-blue-400 shadow scale-[1.01]'
                                                : ($isPast
                                                    ? 'bg-gray-50 dark:bg-gray-800/30 border-gray-200 dark:border-gray-700 opacity-70'
                                                    : 'bg-white dark:bg-gray-900/20 border-gray-200 dark:border-gray-700  hover:shadow') }}
                                        ">
                                            {{-- Ongoing Badge --}}
                                            @if ($isOngoing)
                                                <div
                                                    class="absolute top-0 right-0 bg-blue-300 text-gray-900 px-4 py-1.5 rounded-bl-xl shadow flex items-center gap-2 z-10">
                                                    <span class="relative flex h-2.5 w-2.5">
                                                        <span
                                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                                        <span
                                                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                                                    </span>
                                                    <span class="text-xs font-bold tracking-wide">BERLANGSUNG</span>
                                                </div>
                                            @endif

                                            @if ($isPast)
                                                <div
                                                    class="absolute top-3 right-3 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-1 rounded-full shadow-sm z-10">
                                                    <span class="text-xs font-semibold">SELESAI</span>
                                                </div>
                                            @endif

                                            <div class="p-5">
                                                <div class="flex flex-col lg:flex-row gap-5">
                                                    {{-- Time Section --}}
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="
                                                            inline-flex flex-col items-center justify-center rounded-xl p-4 min-w-[100px] shadow
                                                            {{ $isOngoing
                                                                ? 'bg-blue-300 text-gray-900 ring-2 ring-blue-100 dark:ring-blue-400'
                                                                : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100' }}
                                                        ">
                                                            <div class="text-3xl font-bold leading-none">
                                                                {{ optional($schedule->period)->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $schedule->period->start_time)->format('H:i') : $schedule->period?->start_date?->format('H:i') ?? '-' }}
                                                            </div>
                                                            <div class="text-xs opacity-90 mt-1">s.d.</div>
                                                            <div class="text-lg font-semibold leading-none">
                                                                {{ optional($schedule->period)->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $schedule->period->end_time)->format('H:i') : $schedule->period?->end_date?->format('H:i') ?? '-' }}
                                                            </div>
                                                            @if ($schedule->period?->ordinal)
                                                                <div
                                                                    class="mt-2.5 px-3 py-1 bg-white/25 backdrop-blur-sm rounded-full">
                                                                    <span class="text-xs font-bold">Jam
                                                                        {{ $schedule->period->ordinal }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Content Section --}}
                                                    <div class="flex-1 min-w-0">
                                                        {{-- Subject --}}
                                                        <div class="mb-4">
                                                            <h4
                                                                class="text-xl font-bold text-gray-900 dark:text-white mb-1 leading-tight">
                                                                {{ $schedule->teacherSubject?->subject?->name ?? '-' }}
                                                            </h4>
                                                            @if ($schedule->teacherSubject?->subject?->code)
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                                    {{ $schedule->teacherSubject->subject->code }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        {{-- Info Grid --}}
                                                        <div class="grid md:grid-cols-3 gap-4">
                                                            {{-- Class --}}
                                                            <div
                                                                class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-100 dark:border-gray-700">
                                                                <div
                                                                    class="flex-shrink-0 w-11 h-11 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center shadow">
                                                                    <x-heroicon-o-user-group
                                                                        class="w-6 h-6 text-white" />
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                                        Kelas</p>
                                                                    <p
                                                                        class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                                                        {{ $schedule->template?->class?->full_name ?? '-' }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            {{-- Teacher (who teaches this) --}}
                                                            <div
                                                                class="flex items-center gap-3 bg-white dark:bg-gray-900 rounded-lg p-3 shadow border border-gray-100 dark:border-gray-800">
                                                                <img src="{{ Storage::url(optional(optional($schedule->teacherSubject)->teacher)->user?->avatar) ?? (Storage::url(optional(optional($schedule->teacherSubject)->teacher)?->avatar) ?? asset('images/default-teacher.png')) }}"
                                                                    alt="Guru"
                                                                    class="w-11 h-11 rounded-full object-cover border-2 shadow
                                                                    {{ $isOngoing ? 'border-blue-300 ring-2 ring-blue-100 dark:ring-blue-400' : 'border-gray-200 dark:border-gray-700' }}">
                                                                <div class="flex-1 min-w-0">
                                                                    <p
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                                        Pengajar</p>
                                                                    <p
                                                                        class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                                                        {{ optional(optional($schedule->teacherSubject)->teacher)->user?->name ?? (optional(optional($schedule->teacherSubject)->teacher)?->name ?? '-') }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            {{-- Room --}}
                                                            <div
                                                                class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-100 dark:border-gray-700">
                                                                <div
                                                                    class="flex-shrink-0 w-11 h-11 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center shadow">
                                                                    <x-heroicon-o-map-pin class="w-6 h-6 text-white" />
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                                        Ruangan</p>
                                                                    <p
                                                                        class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                                                        {{ $schedule->roomHistory?->room?->name ?? '-' }}
                                                                    </p>
                                                                    @if ($schedule->roomHistory?->room?->building?->name)
                                                                        <p
                                                                            class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                                            {{ $schedule->roomHistory->room->building->name }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


