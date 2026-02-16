<x-guest-layout title="Cari Guru" description="Temukan guru berdasarkan nama atau kode">
    <section class="pt-28 pb-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-4xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8">
                <a href="{{ route('views.views') }}"
                    class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Views</a>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
                <span class="text-slate-900 dark:text-white font-medium">Cari Guru</span>
            </nav>

            {{-- Header --}}
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">Cari Guru</h1>
                <p class="text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                    Masukkan nama atau kode guru untuk menemukan lokasi mengajar dan jadwal saat ini.
                </p>
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('views.search-teacher') }}" class="mb-10">
                <div class="relative max-w-2xl mx-auto">
                    <x-heroicon-o-magnifying-glass
                        class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input type="text" name="q" value="{{ $query }}"
                        placeholder="Ketik nama atau kode guru..." autofocus
                        class="w-full pl-12 pr-24 py-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white text-base shadow-sm focus:ring-2 focus:ring-slate-400 focus:border-transparent outline-none transition" />
                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2 bg-slate-800 dark:bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-900 dark:hover:bg-slate-600 transition">
                        Cari
                    </button>
                </div>
            </form>

            {{-- Results --}}
            @if (strlen($query) >= 2)
                @if ($results->isNotEmpty())
                    <div class="space-y-4">
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                            Ditemukan <span
                                class="font-medium text-slate-700 dark:text-slate-300">{{ $results->count() }}</span>
                            hasil untuk "<span class="font-medium">{{ $query }}</span>"
                        </p>

                        @foreach ($results as $result)
                            <div
                                class="bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-5 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                    {{-- Avatar --}}
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-14 h-14 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-lg font-semibold text-slate-600 dark:text-slate-300">
                                            {{ $result['user']->initials() }}
                                        </div>
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <div>
                                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                                    {{ $result['user']->name }}</h3>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    @if ($result['teacher']->code)
                                                        <span class="font-mono">Kode:
                                                            {{ $result['teacher']->code }}</span>
                                                    @endif
                                                    @if ($result['teacher']->phone)
                                                        · {{ $result['teacher']->phone }}
                                                    @endif
                                                </p>
                                            </div>

                                            {{-- Attendance --}}
                                            <span
                                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium
                                                {{ $result['attendanceStatus'] === 'hadir'
                                                    ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800'
                                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full {{ $result['attendanceStatus'] === 'hadir' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                {{ $result['attendanceStatus'] === 'hadir' ? 'Hadir' : 'Belum Hadir' }}
                                            </span>
                                        </div>

                                        {{-- Current Location --}}
                                        @if ($result['currentEntry'])
                                            <div
                                                class="mt-3 p-3 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                                <p
                                                    class="text-xs text-slate-500 dark:text-slate-400 mb-2 font-medium uppercase tracking-wider">
                                                    Sedang Mengajar</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                    <div class="flex items-center gap-1.5">
                                                        <x-heroicon-o-building-office
                                                            class="w-3.5 h-3.5 text-slate-400" />
                                                        <span
                                                            class="text-sm text-slate-700 dark:text-slate-300">{{ $result['currentRoom']?->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <x-heroicon-o-book-open class="w-3.5 h-3.5 text-slate-400" />
                                                        <span
                                                            class="text-sm text-slate-700 dark:text-slate-300">{{ $result['currentSubject']?->name ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <x-heroicon-o-users class="w-3.5 h-3.5 text-slate-400" />
                                                        <span
                                                            class="text-sm text-slate-700 dark:text-slate-300">{{ $result['currentClassroom']?->full_name ?? '-' }}</span>
                                                    </div>
                                                </div>

                                                @if ($result['currentRoom']?->building)
                                                    <div class="mt-2 flex items-center gap-1.5">
                                                        <x-heroicon-o-map-pin class="w-3.5 h-3.5 text-slate-400" />
                                                        <span class="text-xs text-slate-500 dark:text-slate-400">
                                                            {{ $result['currentRoom']->building->name }}, Lt.
                                                            {{ $result['currentRoom']->floor ?? '-' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div
                                                class="mt-3 p-3 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                                <p class="text-xs text-slate-400 dark:text-slate-500 italic">Tidak
                                                    sedang mengajar saat ini</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- No results --}}
                    <div class="text-center py-16">
                        <x-heroicon-o-face-frown class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" />
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Guru tidak ditemukan</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada hasil untuk
                            "{{ $query }}". Coba kata kunci lain.</p>
                    </div>
                @endif
            @elseif(strlen($query) > 0)
                <div class="text-center py-16">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Masukkan minimal 2 karakter untuk mencari.</p>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <x-heroicon-o-academic-cap class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Cari Guru</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                        Masukkan nama atau kode guru untuk melihat lokasi mengajar, jadwal, dan status kehadiran.
                    </p>
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>
