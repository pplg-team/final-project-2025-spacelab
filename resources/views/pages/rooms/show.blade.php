<x-guest-layout title="Detail Ruangan" description="Lihat detail ruangan, jadwal, dan penggunaan">
    <section class="pt-28 pb-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-5xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8">
                <a href="{{ route('views.views') }}"
                    class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Views</a>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
                <a href="{{ route('views.rooms') }}"
                    class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Ruangan</a>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
                <span class="text-slate-900 dark:text-white font-medium">{{ $room->name }}</span>
            </nav>

            {{-- Room Info Card --}}
            <div
                class="bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 mb-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                                <x-heroicon-o-building-office-2 class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $room->name }}</h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $room->code }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Gedung</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $room->building?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Lantai</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $room->floor ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Kapasitas</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $room->capacity ?? '-' }} orang</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Tipe</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ ucfirst($room->type ?? '-') }}</p>
                            </div>
                        </div>

                        @if ($room->notes)
                            <div
                                class="mt-4 p-3 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Catatan</p>
                                <p class="text-sm text-slate-700 dark:text-slate-300">{{ $room->notes }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Status --}}
                    <div class="flex-shrink-0">
                        @if ($ongoingEntry)
                            <div
                                class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl p-4 text-center min-w-[160px]">
                                <span
                                    class="flex items-center justify-center gap-1.5 text-sm font-medium text-amber-700 dark:text-amber-400 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    Sedang Digunakan
                                </span>
                                <p class="text-xs text-amber-600 dark:text-amber-400/80 mt-1">
                                    {{ $ongoingEntry->teacherSubject?->subject?->name ?? '-' }}
                                </p>
                            </div>
                        @else
                            <div
                                class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 text-center min-w-[160px]">
                                <span
                                    class="flex items-center justify-center gap-1.5 text-sm font-medium text-emerald-700 dark:text-emerald-400">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Kosong
                                </span>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400/80 mt-1">Tidak ada kelas saat
                                    ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Today's Schedule --}}
            <div
                class="bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Jadwal Hari Ini</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $dayName }},
                                {{ $today->translatedFormat('d F Y') }}</p>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $todayEntries->count() }}
                            sesi</span>
                    </div>
                </div>

                @if ($todayEntries->isNotEmpty())
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($todayEntries as $entry)
                            @php
                                $isOngoing = $entry->isOngoing($now);
                                $isPast = $entry->isPast($now);
                            @endphp
                            <div
                                class="px-6 py-4 flex items-center gap-4 {{ $isOngoing ? 'bg-slate-50 dark:bg-slate-900/50 border-l-4 border-l-amber-500' : ($isPast ? 'opacity-60' : '') }}">
                                {{-- Time --}}
                                <div class="flex-shrink-0 text-center min-w-[80px]">
                                    <p class="text-sm font-mono font-medium text-slate-900 dark:text-white">
                                        {{ $entry->period?->start_time ? \Carbon\Carbon::parse($entry->period->start_time)->format('H:i') : '--:--' }}
                                    </p>
                                    <p class="text-xs font-mono text-slate-400 dark:text-slate-500">
                                        {{ $entry->period?->end_time ? \Carbon\Carbon::parse($entry->period->end_time)->format('H:i') : '--:--' }}
                                    </p>
                                </div>

                                {{-- Status Indicator --}}
                                <div class="flex-shrink-0">
                                    @if ($isOngoing)
                                        <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse block"></span>
                                    @elseif($isPast)
                                        <span class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 block"></span>
                                    @else
                                        <span
                                            class="w-3 h-3 rounded-full border-2 border-slate-300 dark:border-slate-600 block"></span>
                                    @endif
                                </div>

                                {{-- Entry Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                        {{ $entry->teacherSubject?->subject?->name ?? 'Tidak ada data' }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                        {{ $entry->template?->class?->full_name ?? '-' }}
                                    </p>
                                </div>

                                {{-- Teacher --}}
                                <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-medium text-slate-600 dark:text-slate-300">
                                        {{ $entry->teacherSubject?->teacher?->user ? $entry->teacherSubject->teacher->user->initials() : '?' }}
                                    </div>
                                    <span class="text-xs text-slate-600 dark:text-slate-400 max-w-[120px] truncate">
                                        {{ $entry->teacherSubject?->teacher?->user?->name ?? '-' }}
                                    </span>
                                </div>

                                {{-- Status Label --}}
                                <div class="flex-shrink-0">
                                    @if ($isOngoing)
                                        <span
                                            class="text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 px-2 py-1 rounded-full">Berlangsung</span>
                                    @elseif($isPast)
                                        <span class="text-xs text-slate-400 dark:text-slate-500">Selesai</span>
                                    @else
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Akan datang</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-heroicon-o-calendar class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada jadwal untuk ruangan ini hari
                            ini</p>
                    </div>
                @endif
            </div>

            {{-- QR Code Section --}}
            @if ($activeSession)
                <div class="bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-6 md:p-8"
                    x-data="{ showQr: false }">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">QR Code Absensi</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Sesi absensi aktif untuk ruangan ini
                            </p>
                        </div>
                        <button @click="showQr = !showQr"
                            class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white text-sm rounded-lg hover:bg-slate-900 dark:hover:bg-slate-600 transition">
                            <span x-text="showQr ? 'Sembunyikan' : 'Tampilkan QR'"></span>
                        </button>
                    </div>

                    <div x-show="showQr" x-collapse>
                        <div class="flex flex-col items-center gap-4 py-6">
                            <div id="qr-code-room" class="bg-white p-4 rounded-xl"></div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Token: <code
                                    class="font-mono">{{ $activeSession->token }}</code></p>
                        </div>
                    </div>

                    @push('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
                        <script>
                            document.addEventListener('alpine:init', () => {
                                const qr = qrcode(0, 'M');
                                qr.addData('{{ $activeSession->token }}');
                                qr.make();
                                const el = document.getElementById('qr-code-room');
                                if (el) el.innerHTML = qr.createImgTag(6, 0);
                            });
                        </script>
                    @endpush
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>
