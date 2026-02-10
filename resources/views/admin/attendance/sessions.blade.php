<x-app-layout title="Kelola Absensi" description="Monitoring & Statistik Kehadiran Harian">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Absensi') }}
        </h2>
    </x-slot>

    <!-- 1. Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <!-- Hari Ini -->
        <div class="bg-indigo-600 rounded-lg shadow-md p-4 text-white">
            <div class="text-xs font-semibold uppercase tracking-wider mb-1">Hari Ini</div>
            <div class="text-xl font-bold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
        </div>

        <!-- Staff -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1">Staff Hadir
            </div>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['staff'] }}</span>
                <span class="text-sm text-gray-500 mb-1">/ {{ $counts['staff'] }}</span>
            </div>
        </div>

        <!-- Guru -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1">Guru Hadir
            </div>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['guru'] }}</span>
                <span class="text-sm text-gray-500 mb-1">/ {{ $counts['guru'] }}</span>
            </div>
        </div>

        <!-- Siswa -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1">Siswa
                Hadir</div>
            <div class="flex items-end gap-2">
                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['siswa'] }}</span>
                <span class="text-sm text-gray-500 mb-1">/ {{ $counts['siswa'] }}</span>
            </div>
        </div>

        <!-- Total -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-gray-500">
            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1">Total
                Hadir</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
        </div>
    </div>

    <!-- 2. Chart & Activation -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Weekly Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Grafik Kehadiran Mingguan</h3>
            <div class="h-64">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Activation Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Aktivasi Absensi Hari Ini</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                        <span class="text-gray-600 dark:text-gray-400">Status Absensi</span>
                        @if ($isAbsensiActive)
                            <span
                                class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">AKTIF</span>
                        @else
                            <span
                                class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">NONAKTIF</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Sesi Aktif</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $activeSessionsCount }}
                            Kelas</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3">
                @if (!$isAbsensiActive && !$sessionTodayExists)
                    <form action="{{ route('admin.attendance.store') }}" method="POST">
                        @csrf
                        {{-- Dummy ID, controller handles finding sessions --}}
                        <input type="hidden" name="timetable_entry_id" value="0">
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-1">
                            BUKA ABSENSI HARI INI
                        </button>
                    </form>
                @elseif ($sessionTodayExists && !$isAbsensiActive)
                    <button disabled
                        class="w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed shadow-lg"
                        title="Sesi absensi sudah dibuat hari ini">
                        SESI ABSENSI SUDAH DIBUAT
                    </button>
                @elseif ($sessionTodayExists && $isAbsensiActive)
                    <div class="space-y-4">
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg flex flex-col items-center border border-gray-100 dark:border-gray-700">
                            <div id="qrcode" class="bg-white p-2 rounded shadow-sm mb-3"></div>
                            <div class="bg-indigo-50 dark:bg-indigo-900/30 p-2 rounded w-full text-center">
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1 italic">Token Harian
                                    Aktif</p>
                                <p class="text-sm font-bold text-indigo-700 dark:text-indigo-300 break-all font-mono"
                                    id="token-display">
                                    {{ $activeSessionToken }}
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('admin.attendance.destroy', 'bulk') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menutup semua sesi absensi?')"
                                class="w-full bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 shadow-lg transition transform hover:-translate-y-1">
                                TUTUP SEMUA SESI
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Monitoring Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div
            class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Monitoring Absensi</h3>

            <!-- Filters -->
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}"
                    class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-300">

                <select name="role"
                    class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-300">
                    <option value="">Semua Role</option>
                    <option value="Staff" {{ request('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Guru" {{ request('role') == 'Guru' ? 'selected' : '' }}>Guru</option>
                    <option value="Siswa" {{ request('role') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                </select>

                <select name="status"
                    class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-300">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama..."
                    class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-300">

                <button type="submit"
                    class="bg-gray-200 dark:bg-gray-700 px-3 py-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm transition text-gray-700 dark:text-gray-300">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                            / Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($records as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $record->user->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $record->user->role->name == 'Guru' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $record->user->role->name == 'Staff' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $record->user->role->name == 'Siswa' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ $record->user->role->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if ($record->user->role->name == 'Siswa')
                                    {{ $record->attendanceSession->timetableEntry->template->class->full_name ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $record->status == 'hadir' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $record->status == 'izin' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $record->status == 'sakit' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $record->status == 'alpa' ? 'bg-red-500 text-white' : '' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $record->scanned_at ? \Carbon\Carbon::parse($record->scanned_at)->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if ($record->latitude && $record->longitude)
                                    <a href="https://maps.google.com/?q={{ $record->latitude }},{{ $record->longitude }}"
                                        target="_blank"
                                        class="text-indigo-600 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Map
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    onclick="showDetail('{{ $record->id }}', '{{ $record->user->name }}', '{{ $record->selfie_photo }}')"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada data absensi untuk hari ini/filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $records->links() }}
        </div>
    </div>


    <!-- Modal Detail -->
    <div x-data="{ open: false, name: '', photo: '' }"
        @open-detail.window="open = true; name = $event.detail.name; photo = $event.detail.photo"
        class="relative z-50">

        <!-- Backdrop -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50"></div>

        <!-- Modal -->
        <div x-show="open" x-transition @click.outside="open = false"
            class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" x-text="'Detail Absensi: ' + name">
                    </h3>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Foto Selfie:</p>
                        <template x-if="photo">
                            <img :src="'/storage/' + photo" alt="Selfie" class="w-full rounded-lg shadow-sm">
                        </template>
                        <template x-if="!photo">
                            <div
                                class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                                Tidak ada foto
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="open = false"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js & QRCode.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Logic
            const ctx = document.getElementById('weeklyChart').getContext('2d');
            const data = @json($chartData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.day),
                    datasets: [{
                        label: 'Total Kehadiran',
                        data: data.map(item => item.total),
                        backgroundColor: '#4F46E5', // Indigo 600
                        borderRadius: 4,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? '#374151' :
                                    '#e5e7eb'
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#9CA3AF' :
                                    '#4B5563'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#9CA3AF' :
                                    '#4B5563'
                            }
                        }
                    }
                }
            });

            // Init QR Code if token exists
            const token = "{{ $activeSessionToken ?? '' }}";
            if (token) {
                const qrContainer = document.getElementById('qrcode');
                if (qrContainer) {
                    new QRCode(qrContainer, {
                        text: token,
                        width: 180,
                        height: 180,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                    document.getElementById('token-display').innerText = token;
                }
            }
        });

        function showDetail(id, name, photo) {
            window.dispatchEvent(new CustomEvent('open-detail', {
                detail: {
                    id: id,
                    name: name,
                    photo: photo
                }
            }));
        }
    </script>
</x-app-layout>
