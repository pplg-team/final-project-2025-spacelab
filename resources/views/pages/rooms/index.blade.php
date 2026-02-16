<x-guest-layout title="Ruangan & Gedung" description="Jelajahi semua ruangan dan gedung di SpaceLab">
    <section class="pt-28 pb-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900 min-h-screen" x-data="{
        selectedBuilding: '{{ $selectedBuilding ?? '' }}',
        selectedType: '{{ $selectedType ?? '' }}',
        search: '{{ $search ?? '' }}',
        applyFilter() {
            const params = new URLSearchParams();
            if (this.selectedBuilding) params.set('building', this.selectedBuilding);
            if (this.selectedType) params.set('type', this.selectedType);
            if (this.search) params.set('search', this.search);
            window.location.href = '{{ route('views.rooms') }}' + (params.toString() ? '?' + params.toString() : '');
        }
    }">
        <div class="max-w-7xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8">
                <a href="{{ route('views.views') }}"
                    class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Views</a>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
                <span class="text-slate-900 dark:text-white font-medium">Ruangan</span>
            </nav>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Ruangan & Gedung</h1>
                <p class="text-slate-600 dark:text-slate-400">
                    Jelajahi semua ruangan, lihat status penggunaan dan jadwal saat ini —
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-4 mb-8">
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Search --}}
                    <div class="flex-1 relative">
                        <x-heroicon-o-magnifying-glass
                            class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input type="text" x-model="search" @keydown.enter="applyFilter()"
                            placeholder="Cari nama ruangan..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-slate-400 focus:border-transparent outline-none transition" />
                    </div>

                    {{-- Building Filter --}}
                    <select x-model="selectedBuilding" @change="applyFilter()"
                        class="px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-slate-400 focus:border-transparent outline-none transition">
                        <option value="">Semua Gedung</option>
                        @foreach ($buildings as $building)
                            <option value="{{ $building->id }}">{{ $building->name }}</option>
                        @endforeach
                    </select>

                    {{-- Type Filter --}}
                    <select x-model="selectedType" @change="applyFilter()"
                        class="px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-slate-400 focus:border-transparent outline-none transition">
                        <option value="">Semua Tipe</option>
                        @foreach ($roomTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>

                    {{-- Reset --}}
                    <a href="{{ route('views.rooms') }}"
                        class="px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-sm hover:bg-slate-50 dark:hover:bg-slate-900 transition text-center">
                        Reset
                    </a>
                </div>
            </div>

            {{-- Buildings & Rooms --}}
            @foreach ($buildings as $building)
                @php
                    $filteredRooms = $building->rooms;

                    if ($selectedBuilding && $building->id !== $selectedBuilding) {
                        continue;
                    }

                    if ($selectedType) {
                        $filteredRooms = $filteredRooms->where('type', $selectedType);
                    }

                    if ($search) {
                        $filteredRooms = $filteredRooms->filter(function ($room) use ($search) {
                            return str_contains(strtolower($room->name), strtolower($search)) ||
                                str_contains(strtolower($room->code ?? ''), strtolower($search));
                        });
                    }

                    if ($filteredRooms->isEmpty()) {
                        continue;
                    }
                @endphp

                <div class="mb-10">
                    {{-- Building Header --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 bg-slate-200 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-building-office class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $building->name }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $building->description ?? '' }} · {{ $building->total_floors ?? '?' }} lantai ·
                                {{ $filteredRooms->count() }} ruangan
                            </p>
                        </div>
                    </div>

                    {{-- Room Grid --}}
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($filteredRooms as $room)
                            @php
                                $usage = $roomUsageMap[$room->id] ?? null;
                                $isOccupied = $usage !== null;
                            @endphp
                            <a href="{{ route('views.rooms.show', $room) }}"
                                class="group bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-5 hover:border-slate-400 dark:hover:border-slate-600 transition-all duration-200 hover:shadow-md">
                                {{-- Status Badge --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs font-mono text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $room->code }}</span>
                                        @if ($room->type)
                                            <span
                                                class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ ucfirst($room->type) }}</span>
                                        @endif
                                    </div>
                                    <span
                                        class="flex items-center gap-1 text-xs font-medium {{ $isOccupied ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        <span
                                            class="w-2 h-2 rounded-full {{ $isOccupied ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                        {{ $isOccupied ? 'Terpakai' : 'Kosong' }}
                                    </span>
                                </div>

                                {{-- Room Name --}}
                                <h3
                                    class="font-semibold text-slate-900 dark:text-white mb-1 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                                    {{ $room->name }}
                                </h3>

                                {{-- Meta --}}
                                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-3">
                                    <span class="flex items-center gap-1">
                                        <x-heroicon-o-arrow-up class="w-3 h-3" />
                                        Lt. {{ $room->floor ?? '-' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <x-heroicon-o-users class="w-3 h-3" />
                                        {{ $room->capacity ?? '-' }}
                                    </span>
                                </div>

                                {{-- Current Usage --}}
                                @if ($isOccupied)
                                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                                        <p class="text-xs text-slate-600 dark:text-slate-400 truncate">
                                            <span
                                                class="font-medium text-slate-700 dark:text-slate-300">{{ $usage->teacherSubject?->subject?->name ?? '-' }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                            {{ $usage->teacherSubject?->teacher?->user?->name ?? '-' }} ·
                                            {{ $usage->template?->class?->full_name ?? '-' }}
                                        </p>
                                    </div>
                                @else
                                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                                        <p class="text-xs text-slate-400 dark:text-slate-500 italic">Tidak ada kelas
                                            saat ini</p>
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Empty state --}}
            @if ($buildings->every(fn($b) => $b->rooms->isEmpty()))
                <div class="text-center py-20">
                    <x-heroicon-o-building-office-2 class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Belum ada data ruangan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Data ruangan dan gedung belum tersedia.</p>
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>
