<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Manajemen Ruangan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Section 2: History Table - Modern Design -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Penggunaan Ruangan</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Kelola dan lacak alokasi ruangan</p>
                        </div>
                        <button onclick="openModal('create')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                            <x-heroicon-o-plus class="w-5 h-5" />
                            <span>Tambah Alokasi</span>
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Ruangan
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Kelas
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Guru
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Tahun Ajaran
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Tipe Event
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Periode
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($histories as $history)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $history->room->code ?? '-' }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $history->room->name ?? 'Ruangan' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $history->classroom->full_name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $history->teacher->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $history->term->tahun_ajaran ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ ucfirst($history->term->kind ?? '-') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                            {{ $history->event_type ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $history->start_date?->format('d M Y H:i') ?? '-' }}</span>
                                            <!-- Contoh output: 04 Jan 2026 15:30 -->
                                        </div>
                                        <div class="flex items-center gap-1 mt-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                            <span>{{ $history->end_date?->format('d M Y H:i') ?? '-' }}</span>
                                            <!-- Contoh output: 04 Jan 2026 18:45 -->
                                        </div>
                                    </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openModal('edit', {{ json_encode($history) }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                            <button onclick="confirmDelete('{{ $history->id }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12">
                                        <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                            <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-base font-medium">Belum ada riwayat penggunaan</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tambahkan alokasi ruangan untuk memulai</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($histories->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        {{ $histories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal - Modern Design -->
    <x-modal name="historyModal" :show="false" focusable>
        <form id="historyForm" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-800 dark:to-gray-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modalTitle">Tambah Alokasi Ruangan</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Lengkapi informasi alokasi ruangan</p>
            </div>

            <div class="px-6 py-5 space-y-5 max-h-[65vh] overflow-y-auto">
                <!-- Date & Time (moved up) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <!-- Start DateTime -->
                    <div>
                        <x-input-label for="start_datetime" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal & Jam Mulai <span class="text-red-500">*</span>
                            </div>
                        </x-input-label>
                        <input id="start_datetime" name="start_date" type="datetime-local" required
                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors">
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <!-- End DateTime -->
                    <div>
                        <x-input-label for="end_datetime" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal & Jam Selesai <span class="text-red-500">*</span>
                            </div>
                        </x-input-label>
                        <input id="end_datetime" name="end_date" type="datetime-local" required
                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors">
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <!-- Room -->
                <div>
                    <x-input-label for="room_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Ruangan <span class="text-red-500">*</span>
                        </div>
                    </x-input-label>
                    <select name="room_id" id="room_id" required disabled
                        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors">
                        <option value="">Pilih Ruangan setelah memilih tanggal & jam</option>
                    </select>
                    <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                </div>

                <!-- Class -->
                <div>
                    <x-input-label for="classes_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Kelas <span class="text-gray-400 text-xs">(Opsional)</span>
                        </div>
                    </x-input-label>
                    <select name="classes_id" id="classes_id" disabled
                        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors">
                        <option value="">Pilih Kelas setelah memilih Ruangan</option>
                    </select>
                </div>

                <!-- Teacher -->
                <div>
                    <x-input-label for="teacher_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Guru Pengajar <span class="text-gray-400 text-xs">(Opsional)</span>
                        </div>
                    </x-input-label>
                    <select name="teacher_id" id="teacher_id" disabled
                        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors">
                        <option value="">Pilih Guru setelah memilih Kelas</option>
                    </select>
                </div>

                <!-- Term -->
                <div>
                    <x-input-label for="terms_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </div>
                    </x-input-label>
                    <select name="terms_id" id="terms_id" required
                        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}" {{ $term->is_active ? 'selected' : '' }}>
                                {{ $term->tahun_ajaran }} - {{ ucfirst($term->kind) }}
                                {{ $term->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Event Type -->
                <div>
                    <x-input-label for="event_type" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Tipe Event
                        </div>
                    </x-input-label>
                        <x-text-input id="event_type" name="event_type" type="text" disabled
                            class="mt-2 block w-full rounded-lg"
                            placeholder="Contoh: KBM, Rapat, Ujian, Workshop" />
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Kategori kegiatan yang dilakukan di ruangan</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex items-center justify-between gap-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <span class="text-red-500">*</span> Wajib diisi
                </p>
                <div class="flex gap-3">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Data from controller for client-side filtering
        const ALL_ROOMS = @json($allRooms);
        const HISTORIES = @json($histories->items());
        const CLASSROOMS = @json($classrooms);
        const TEACHERS = @json($teachers->map(function($t) { return ['id' => $t->id, 'name' => $t->user->name]; }));

        // Helper to format incoming datetime strings to `datetime-local` compatible value
        function toInputDateTime(value) {
            if (!value) return '';
            // Normalize ' ' to 'T' if necessary
            value = value.replace(' ', 'T');
            return value.substring(0, 16);
        }

        function openModal(mode, data = null) {
            const form = document.getElementById('historyForm');
            const title = document.getElementById('modalTitle');
            const methodInput = document.getElementById('formMethod');

            // Reset form and UI state
            form.reset();
            disableElement('room_id', true);
            disableElement('classes_id', true);
            disableElement('teacher_id', true);
            disableElement('event_type', true);

            if (mode === 'create') {
                form.action = "{{ route('admin.rooms.history.store') }}";
                methodInput.value = 'POST';
                title.innerText = 'Tambah Alokasi Ruangan';
            } else {
                form.action = `/admin/room-history/${data.id}`;
                methodInput.value = 'PUT';
                title.innerText = 'Edit Alokasi Ruangan';

                // Fill data
                document.getElementById('start_datetime').value = toInputDateTime(data.start_date ?? '');
                document.getElementById('end_datetime').value = toInputDateTime(data.end_date ?? '');
                // populate rooms based on selected datetime then set values
                if (data.start_date && data.end_date) {
                    fetchAvailableRooms().then(() => {
                        document.getElementById('room_id').value = data.room_id || '';
                        if (data.room_id) {
                            fetchAvailableClasses().then(() => {
                                document.getElementById('classes_id').value = data.classes_id || '';
                                if (data.classes_id) {
                                    fetchAvailableTeachers();
                                    document.getElementById('teacher_id').value = data.teacher_id || '';
                                }
                            });
                        }
                    });
                }

                document.getElementById('terms_id').value = data.terms_id || '';
                document.getElementById('event_type').value = data.event_type || '';
            }

            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'historyModal'
            }));
        }

        function confirmDelete(id) {
            if (confirm('⚠️ Apakah Anda yakin ingin menghapus riwayat ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/room-history/${id}`;
                form.submit();
            }
        }

        // Generic helpers
        function disableElement(id, state) {
            const el = document.getElementById(id);
            if (!el) return;
            el.disabled = state;
        }

        function populateSelect(id, items, placeholder) {
            const sel = document.getElementById(id);
            if (!sel) return;
            sel.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = placeholder;
            sel.appendChild(opt);
            items.forEach(i => {
                const o = document.createElement('option');
                o.value = i.id;
                o.textContent = i.label || i.name || i.full_name || `${i.code || ''} ${i.name || ''}`.trim();
                if (i.disabled) o.disabled = true;
                sel.appendChild(o);
            });
        }

        // Client-side filtering using controller-provided data only
        function parseDT(val) {
            if (!val) return null;
            const v = val.replace(' ', 'T');
            const d = new Date(v);
            return isNaN(d.getTime()) ? null : d;
        }

        function overlaps(aStart, aEnd, bStart, bEnd) {
            if (!aStart || !aEnd || !bStart || !bEnd) return false;
            return aStart < bEnd && bStart < aEnd;
        }

        function fetchAvailableRooms() {
            const start = parseDT(document.getElementById('start_datetime').value);
            const end = parseDT(document.getElementById('end_datetime').value);
            if (!start || !end) return;
            populateSelect('room_id', [], 'Memuat...');
            disableElement('room_id', false);
            try {
                const items = ALL_ROOMS.filter(r => {
                    const conflict = HISTORIES.find(h => (h.room_id == r.id) && overlaps(start, end, parseDT(h.start_date), parseDT(h.end_date)));
                    return !conflict;
                }).map(r => ({ id: r.id, label: `${r.code || ''} ${r.name || ''}`.trim() }));
                populateSelect('room_id', items, items.length ? 'Pilih Ruangan' : 'Tidak ada ruangan tersedia');
                disableElement('classes_id', true);
                disableElement('teacher_id', true);
                disableElement('event_type', true);
            } catch (err) {
                populateSelect('room_id', [], 'Gagal memuat ruangan');
                console.error(err);
            }
        }

        function fetchAvailableClasses() {
            const start = parseDT(document.getElementById('start_datetime').value);
            const end = parseDT(document.getElementById('end_datetime').value);
            const roomId = document.getElementById('room_id').value;
            if (!roomId) return;
            populateSelect('classes_id', [], 'Memuat...');
            disableElement('classes_id', false);
            try {
                const items = CLASSROOMS.map(c => {
                    const conflict = HISTORIES.find(h => (h.classes_id == c.id) && overlaps(start, end, parseDT(h.start_date), parseDT(h.end_date)));
                    return { id: c.id, label: c.full_name || c.name || c.id, disabled: !!conflict };
                });
                populateSelect('classes_id', items, 'Pilih Kelas (opsional)');
                disableElement('teacher_id', true);
                disableElement('event_type', true);
            } catch (err) {
                populateSelect('classes_id', [], 'Gagal memuat kelas');
                console.error(err);
            }
        }

        function fetchAvailableTeachers() {
            const start = parseDT(document.getElementById('start_datetime').value);
            const end = parseDT(document.getElementById('end_datetime').value);
            const roomId = document.getElementById('room_id').value;
            const classId = document.getElementById('classes_id').value;
            if (!roomId) return;
            populateSelect('teacher_id', [], 'Memuat...');
            disableElement('teacher_id', false);
            try {
                const items = TEACHERS.map(t => {
                    const conflict = HISTORIES.find(h => (h.teacher_id == t.id) && overlaps(start, end, parseDT(h.start_date), parseDT(h.end_date)));
                    return { id: t.id, label: t.name || t.user?.name || t.id, disabled: !!conflict };
                });
                populateSelect('teacher_id', items, 'Pilih Guru (opsional)');
                disableElement('event_type', false);
            } catch (err) {
                populateSelect('teacher_id', [], 'Gagal memuat guru');
                console.error(err);
            }
        }

        // Wire up events
        document.addEventListener('DOMContentLoaded', function() {
            const start = document.getElementById('start_datetime');
            const end = document.getElementById('end_datetime');
            const room = document.getElementById('room_id');
            const classes = document.getElementById('classes_id');
            const teacher = document.getElementById('teacher_id');

            if (start && end) {
                const handleDateChange = function() {
                    // Basic validation: ensure end >= start
                    if (end.value && start.value && end.value < start.value) {
                        end.value = start.value;
                    }
                    // Fetch available rooms for the new window
                    fetchAvailableRooms();
                };
                start.addEventListener('change', handleDateChange);
                end.addEventListener('change', handleDateChange);
            }

            if (room) {
                room.addEventListener('change', function() {
                    // When room selected, fetch classes
                    if (room.value) {
                        fetchAvailableClasses();
                    } else {
                        populateSelect('classes_id', [], 'Pilih Kelas setelah memilih Ruangan');
                        disableElement('classes_id', true);
                        populateSelect('teacher_id', [], 'Pilih Guru setelah memilih Kelas');
                        disableElement('teacher_id', true);
                        disableElement('event_type', true);
                    }
                });
            }

            if (classes) {
                classes.addEventListener('change', function() {
                    if (classes.value) {
                        fetchAvailableTeachers();
                    } else {
                        populateSelect('teacher_id', [], 'Pilih Guru setelah memilih Kelas');
                        disableElement('teacher_id', true);
                        disableElement('event_type', true);
                    }
                });
            }

            if (teacher) {
                teacher.addEventListener('change', function() {
                    // when teacher selected (or cleared), toggle event_type
                    disableElement('event_type', !teacher.value);
                });
            }
        });
    </script>
</x-app-layout>
