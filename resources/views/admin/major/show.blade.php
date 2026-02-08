<x-app-layout :title="$title" :description="$description">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lihat Jurusan') }}
        </h2>
    </x-slot>
    <div>
        <a href="{{ route('admin.majors.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                bg-gradient-to-r from-gray-800 to-gray-700
                dark:from-gray-700 dark:to-gray-600
                text-white text-sm font-semibold
                shadow-md hover:shadow-lg
                hover:from-gray-700 hover:to-gray-600
                dark:hover:from-gray-600 dark:hover:to-gray-500
                transition-all duration-200 focus:outline-none focus:ring-2
                focus:ring-offset-2 focus:ring-gray-500">

            <x-heroicon-o-arrow-left class="w-5 h-5" />
            <span>Kembali</span>
        </a>
    </div>
    <div class="py-6">
        <!-- Header Card with Avatar -->
        <div
            class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition-all duration-150 mb-6">
            <div
                class="relative h-40 overflow-hidden rounded-xl
                    bg-gradient-to-r from-gray-50 to-gray-100
                    dark:from-gray-700 dark:to-gray-800
                    shadow-sm">

                <div
                    class="absolute -bottom-10 -right-10 w-44 h-44
                        bg-gray-200/60 dark:bg-gray-600/40
                        blur-3xl rounded-full">
                </div>

                <!-- Subtle bottom border glow -->
                <div
                    class="absolute bottom-0 left-0 right-0 h-px
                        bg-gradient-to-r from-transparent via-gray-300 to-transparent
                        dark:via-gray-600">
                </div>
            </div>
            <div class="px-6 pb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12">
                    <!-- Avatar -->
                    <div class="relative mb-4 sm:mb-0">
                        <img src="{{ $major && $major->logo ? Storage::url($major->logo) : asset('assets/images/avatar/default-profile.png') }}"
                            alt="Avatar of username"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                    </div>

                    <!-- Name and Role -->
                    <div class="sm:ml-6 text-center sm:text-left flex-1 z-0">
                        <h3 class="text-4xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $major?->name ?? '— Tidak ada jurusan —' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $major?->code ?? '' }}
                        </p>
                        <div class="mt-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                {{ $major?->slogan ?? 'Tidak ada deskripsi.' }}
                            </span>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-3">
                            @if ($major?->contact_email)
                                <a href="mailto:{{ $major->contact_email }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-heroicon-o-envelope class="w-4 h-4" />
                                    {{ $major->contact_email }}
                                </a>
                            @endif
                            @if ($major?->website)
                                <a href="{{ $major->website }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-heroicon-o-globe-alt class="w-4 h-4" />
                                    {{ $major->website }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div>
                        <div class="mb-4">
                            <p>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">

                                    {{ $activeTerm
                                        ? 'Semester ' . ucfirst($activeTerm->kind) . ' Tahun Ajaran ' . $activeTerm->tahun_ajaran
                                        : 'Tahun Ajaran Tidak Diketahui'
                                    }}

                                </span>
                            </p>
                        </div>
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-major-modal')"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 shadow-sm mt-5">
                            <x-heroicon-o-pencil class="w-5 h-5 mr-3" />
                            Edit Profil
                        </button>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="mt-6 text-gray-600 dark:text-gray-300 text-justify">
                        {{ $major?->description ?? 'Detail jurusan belum di atur.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 md:gap-4">

        <article role="article" aria-label="Pelajaran Hari Ini"
            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Total Kelas</p>
                    <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white">
                        {{ $stats['class_count'] ?? 0 }}</h3>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                    <span class="sr-only">Icon</span>
                    <x-heroicon-o-book-open class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                        aria-hidden="true" />
                </div>
            </div>
        </article>

        <article role="article" aria-label="Pelajaran Hari Ini"
            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Total Siswa</p>
                    <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white">
                        {{ $stats['student_count'] ?? 0 }}</h3>
                    <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">Siswa</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                    <span class="sr-only">Icon</span>
                    <x-heroicon-o-book-open class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                        aria-hidden="true" />
                </div>
            </div>
        </article>

        <article role="article" aria-label="Jumlah Ruangan"
            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Jumlah ruangan</p>
                    <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white">
                        {{ $stats['room_count'] ?? 0 }}</h3>
                    <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">jurusan</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                    <span class="sr-only">Icon</span>
                    <x-heroicon-o-building-office-2 class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                        aria-hidden="true" />
                </div>
            </div>
        </article>

        <article role="article" aria-label="Total Pelajaran Jurusan"
            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Pelajaran Jurusan</p>
                    <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white truncate">
                        {{ $subjectCount ?? ($majorSubjects->count() ?? 0) }}</h3>
                    <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">Mapel</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                    <span class="sr-only">Icon</span>
                    <x-heroicon-o-academic-cap class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                        aria-hidden="true" />
                </div>
            </div>
        </article>

        <article role="article" aria-label="Jumlah Guru Jurusan"
            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Guru Jurusan</p>
                    <h3 class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white truncate">
                        {{ $teacherCount ?? (is_countable($teachers) ? count($teachers) : 0) }}</h3>
                    <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 mt-2">Guru</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800 p-2 md:p-3 rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700">
                    <span class="sr-only">Icon</span>
                    <x-heroicon-o-user-group class="w-5 h-5 md:w-6 md:h-6 text-gray-500 dark:text-gray-100"
                        aria-hidden="true" />
                </div>
            </div>
        </article>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6 py-12">
        <!-- Class List - 2/3 width -->
        <div class="lg:col-span-2">
            <div>
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition-all duration-150">
                    <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <x-heroicon-o-users class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700 dark:text-gray-200" />
                                Daftar Kelas jurusan ini
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            @if ($classes->isEmpty())
                                <p class="text-sm text-gray-500">Tidak ada kelas untuk jurusan ini.</p>
                            @else
                                <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach ($classes as $class)
                                        <li class="py-3 flex items-center justify-between">
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $class->full_name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Siswa:
                                                    {{ $class->students_count ?? 0 }}</div>
                                            </div>
                                            <div class="flex-shrink-0 text-xs text-gray-400">
                                                <a href="{{ route('admin.classrooms.show', $class->id) }}">Lihat Detail</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-3">
                                    @if (method_exists($classes, 'links'))
                                        {{ $classes->appends(request()->except('classes_page'))->links() }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div
                    class="mt-10 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition-all duration-150">
                    <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <x-heroicon-o-users class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700 dark:text-gray-200" />
                                Daftar Guru jurusan
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            @if ($teachers->isEmpty())
                                <p class="text-sm text-gray-500">Belum ada guru yang terhubung ke jurusan ini.</p>
                            @else
                                <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach ($teachers as $t)
                                        <li class="py-3 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $t->teacher?->avatar ?? ($t->avatar ?? asset('assets/images/avatar/default-profile.png')) }}"
                                                    alt="{{ $t->user?->name ?? 'Guru' }}"
                                                    class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700 shadow-sm" />
                                                <div class="min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $t->user?->name ?? '—' }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $t->code ?? '' }}</div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-3">
                                    @if (method_exists($teachers, 'links'))
                                        {{ $teachers->appends(request()->except('teachers_page'))->links() }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="space-y-6">
                <div
                    class="bg-white  dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition-all duration-150">
                    <!-- Current Day Info -->
                    <div
                        class="p-3 mb-5 sm:p-5 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center shadow-sm">
                                <x-heroicon-o-calendar
                                    class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700 dark:text-gray-200" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                    Hari Ini</p>
                                <div class="flex justify-between items-center gap-4">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ now()->translatedFormat('l, d F Y') }}</p>
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ now()->translatedFormat('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-data="{ roleType: '', roleLabel: '' }">
                        <article role="article" aria-label="Kepala Jurusan"
                            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150 mb-6">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Kepala Jurusan
                                    </p>
                                    <h3
                                        class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white truncate">
                                        {{ $assignment?->head?->user?->name ?? 'Belum di atur' }}
                                    </h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="roleType = 'head'; roleLabel = 'Kepala Jurusan'; $dispatch('open-modal', 'role-assignment-modal')"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </button>
                                    <div class="flex-shrink-0">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800 p-1 md:p-2 rounded-full border border-gray-100 dark:border-gray-700">
                                            <img src="{{ $assignment?->head?->user?->avatar ? asset($assignment->head->user->avatar) : asset('assets/images/avatar/default-profile.png') }}"
                                                alt="Avatar Guru"
                                                class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover shadow" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article role="article" aria-label="Kepala Program"
                            class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 p-4 md:p-5 hover:shadow-md transition-all duration-150">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1">Kepala Program
                                    </p>
                                    <h3
                                        class="text-sm md:text-2xl font-extrabold text-gray-900 dark:text-white truncate">
                                        {{ $assignment?->programCoordinator?->user?->name ?? 'Belum di atur' }}
                                    </h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="roleType = 'coordinator'; roleLabel = 'Kepala Program'; $dispatch('open-modal', 'role-assignment-modal')"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </button>
                                    <div class="flex-shrink-0">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800 p-1 md:p-2 rounded-full border border-gray-100 dark:border-gray-700">
                                            <img src="{{ $assignment?->programCoordinator?->user?->avatar ? asset($assignment->programCoordinator->user->avatar) : asset('assets/images/avatar/default-profile.png') }}"
                                                alt="Avatar Guru"
                                                class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover shadow" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- Perusahaan -->
                    <div
                        class="mt-10 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition-all duration-150">
                        <div
                            class="p-3 sm:p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                            <h3
                                class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2">
                                <x-heroicon-o-building-office class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                Perusahaan Mitra
                            </h3>
                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'add-company-modal')"
                                class="text-xs flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                <x-heroicon-o-plus class="w-4 h-4" />
                                Tambah Mitra
                            </button>
                        </div>
                        <div class="p-4 sm:p-6">
                            @if ($companies->isEmpty())
                                <p class="text-sm text-gray-500 text-center py-8">Belum ada perusahaan yang bekerja
                                    sama dengan jurusan ini.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach ($companies as $rel)
                                        @php $company = $rel->company; @endphp
                                        <div
                                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-150 relative">
                                            <!-- Action Buttons (Top Right) -->
                                            <div class="absolute top-4 right-4 flex items-center gap-2">
                                                <button x-data=""
                                                    @click="$dispatch('open-modal', 'edit-company-modal-{{ $rel->id }}')"
                                                    class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                                    title="Edit">
                                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                                </button>
                                                <button x-data=""
                                                    @click="$dispatch('open-modal', 'delete-company-modal-{{ $rel->id }}')"
                                                    class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                    title="Hapus">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>
                                            </div>

                                            <div class="flex items-start gap-4 pr-16">
                                                <!-- Company Logo -->
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $company && $company->logo ? asset($company->logo) : asset('assets/images/company/default.png') }}"
                                                        alt="{{ $company?->name ?? 'Perusahaan' }}"
                                                        class="w-16 h-16 object-contain rounded-lg border border-gray-200 dark:border-gray-600 bg-white p-2" />
                                                </div>

                                                <!-- Company Details -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <div>
                                                            <h4
                                                                class="text-sm font-semibold text-gray-900 dark:text-white">
                                                                {{ $company?->name ?? 'Nama Perusahaan' }}
                                                            </h4>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                                {{ $rel->partnership_type ?? 'Jenis Kemitraan' }}
                                                            </p>
                                                        </div>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $rel->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                            {{ $rel->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                            {{ $rel->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                                            {{ ucfirst($rel->status) }}
                                                        </span>
                                                    </div>

                                                    <!-- Partnership Period -->
                                                    @if ($rel->start_date || $rel->end_date)
                                                        <div
                                                            class="mt-2 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                                            <x-heroicon-o-calendar class="w-3.5 h-3.5" />
                                                            <span>
                                                                @if ($rel->start_date)
                                                                    {{ \Carbon\Carbon::parse($rel->start_date)->format('d M Y') }}
                                                                @endif
                                                                @if ($rel->start_date && $rel->end_date)
                                                                    -
                                                                @endif
                                                                @if ($rel->end_date)
                                                                    {{ \Carbon\Carbon::parse($rel->end_date)->format('d M Y') }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <!-- Links -->
                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                        @if ($company?->website)
                                                            <a href="{{ $company->website }}" target="_blank"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                                                <x-heroicon-o-globe-alt class="w-3.5 h-3.5" />
                                                                Website
                                                            </a>
                                                        @endif
                                                        @if ($rel->document_link)
                                                            <a href="{{ $rel->document_link }}" target="_blank"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                                                <x-heroicon-o-document-text class="w-3.5 h-3.5" />
                                                                Dokumen MoU/MoA
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Company Modal for this relation -->
                                        <x-modal name="edit-company-modal-{{ $rel->id }}" focusable>
                                            <form method="post"
                                                action="{{ route('admin.majors.company-relation.update', [$major->id, $rel->id]) }}"
                                                class="p-6">
                                                @csrf
                                                @method('PUT')

                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('Edit Mitra Perusahaan') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ __('Perbarui informasi kemitraan dengan perusahaan ini.') }}
                                                </p>

                                                <div class="mt-6 space-y-6">
                                                    <!-- Company Select -->
                                                    <div>
                                                        <x-input-label for="company_id_{{ $rel->id }}"
                                                            value="{{ __('Perusahaan') }}" />
                                                        <select id="company_id_{{ $rel->id }}" name="company_id"
                                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                            required>
                                                            @foreach ($allCompanies as $comp)
                                                                <option value="{{ $comp->id }}"
                                                                    {{ $rel->company_id == $comp->id ? 'selected' : '' }}>
                                                                    {{ $comp->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error class="mt-2" :messages="$errors->get('company_id')" />
                                                    </div>

                                                    <!-- Type -->
                                                    <div>
                                                        <x-input-label for="partnership_type_{{ $rel->id }}"
                                                            value="{{ __('Jenis Kemitraan') }}" />
                                                        <x-text-input id="partnership_type_{{ $rel->id }}"
                                                            name="partnership_type" type="text"
                                                            class="mt-1 block w-full"
                                                            value="{{ $rel->partnership_type }}"
                                                            placeholder="Contoh: Magang, Kunjungan Industri" />
                                                        <x-input-error class="mt-2" :messages="$errors->get('partnership_type')" />
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4">
                                                        <!-- Start Date -->
                                                        <div>
                                                            <x-input-label for="start_date_{{ $rel->id }}"
                                                                value="{{ __('Tanggal Mulai') }}" />
                                                            <x-text-input id="start_date_{{ $rel->id }}"
                                                                name="start_date" type="date"
                                                                class="mt-1 block w-full"
                                                                value="{{ $rel->start_date }}" />
                                                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                                                        </div>
                                                        <!-- End Date -->
                                                        <div>
                                                            <x-input-label for="end_date_{{ $rel->id }}"
                                                                value="{{ __('Tanggal Selesai') }}" />
                                                            <x-text-input id="end_date_{{ $rel->id }}"
                                                                name="end_date" type="date"
                                                                class="mt-1 block w-full"
                                                                value="{{ $rel->end_date }}" />
                                                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                                                        </div>
                                                    </div>

                                                    <!-- Status -->
                                                    <div>
                                                        <x-input-label for="status_{{ $rel->id }}"
                                                            value="{{ __('Status') }}" />
                                                        <select id="status_{{ $rel->id }}" name="status"
                                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                            <option value="active"
                                                                {{ $rel->status == 'active' ? 'selected' : '' }}>Aktif
                                                            </option>
                                                            <option value="pending"
                                                                {{ $rel->status == 'pending' ? 'selected' : '' }}>
                                                                Menunggu Konfirmasi</option>
                                                            <option value="inactive"
                                                                {{ $rel->status == 'inactive' ? 'selected' : '' }}>
                                                                Tidak Aktif</option>
                                                        </select>
                                                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                                    </div>

                                                    <!-- Document Link -->
                                                    <div>
                                                        <x-input-label for="document_link_{{ $rel->id }}"
                                                            value="{{ __('Link Dokumen (MoU/MoA)') }}" />
                                                        <x-text-input id="document_link_{{ $rel->id }}"
                                                            name="document_link" type="url"
                                                            class="mt-1 block w-full"
                                                            value="{{ $rel->document_link }}"
                                                            placeholder="https://..." />
                                                        <x-input-error class="mt-2" :messages="$errors->get('document_link')" />
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Batal') }}
                                                    </x-secondary-button>

                                                    <x-primary-button class="ml-3">
                                                        {{ __('Simpan Perubahan') }}
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <!-- Delete Company Modal for this relation -->
                                        <x-modal name="delete-company-modal-{{ $rel->id }}" focusable>
                                            <form method="post"
                                                action="{{ route('admin.majors.company-relation.destroy', [$major->id, $rel->id]) }}"
                                                class="p-6">
                                                @csrf
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('Hapus Mitra Perusahaan') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ __('Apakah Anda yakin ingin menghapus kemitraan dengan') }}
                                                    <strong>{{ $company?->name ?? 'perusahaan ini' }}</strong>?
                                                    {{ __('Tindakan ini tidak dapat dibatalkan.') }}
                                                </p>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Batal') }}
                                                    </x-secondary-button>

                                                    <button type="submit"
                                                        class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                        {{ __('Hapus') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- Daftar Mapel Jurusan --}}
                    <div
                        class="mt-10 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md transition-all duration-150">
                        <div class="p-3 sm:p-5 border-b border-gray-100 dark:border-gray-800">
                            <h3
                                class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2">
                                Pelajaran Jurusan
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            @if ($majorSubjects->isEmpty())
                                <p class="text-sm text-gray-500">Belum ada pelajaran yang ditetapkan untuk jurusan ini.
                                </p>
                            @else
                                <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach ($majorSubjects as $ms)
                                        <li class="py-3 flex items-center justify-between">
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $ms->subject?->name ?? '—' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Kode:
                                                    {{ $ms->subject?->code ?? '—' }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <x-modal name="edit-major-modal" focusable>
        <form method="post" action="{{ route('admin.majors.update', $major->id) }}" class="p-6"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Jurusan') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Perbarui informasi detail jurusan ini.') }}
            </p>

            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Code -->
                <div class="sm:col-span-2">
                    <x-input-label for="code" value="{{ __('Kode') }}" />
                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full"
                        :value="old('code', $major->code)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('code')" />
                </div>

                <!-- Name -->
                <div class="sm:col-span-4">
                    <x-input-label for="name" value="{{ __('Nama Jurusan') }}" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name', $major->name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Slogan -->
                <div class="sm:col-span-6">
                    <x-input-label for="slogan" value="{{ __('Slogan / Tagline') }}" />
                    <x-text-input id="slogan" name="slogan" type="text" class="mt-1 block w-full"
                        :value="old('slogan', $major->slogan)" />
                    <x-input-error class="mt-2" :messages="$errors->get('slogan')" />
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <x-input-label for="description" value="{{ __('Deskripsi') }}" />
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $major->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <!-- Website -->
                <div class="sm:col-span-3">
                    <x-input-label for="website" value="{{ __('Website') }}" />
                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                        :value="old('website', $major->website)" placeholder="https://..." />
                    <x-input-error class="mt-2" :messages="$errors->get('website')" />
                </div>

                <!-- Contact Email -->
                <div class="sm:col-span-3">
                    <x-input-label for="contact_email" value="{{ __('Email Kontak') }}" />
                    <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full"
                        :value="old('contact_email', $major->contact_email)" />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                </div>

                <!-- Logo -->
                <div class="sm:col-span-6">
                    <x-input-label for="logo" value="{{ __('Logo') }}" />
                    <input id="logo" name="logo" type="file"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        accept="image/*">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF
                        (MAX. 2MB).</p>
                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Simpan Perubahan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Add Company Modal -->
    <x-modal name="add-company-modal" focusable>
        <form method="post" action="{{ route('admin.majors.company-relation.store', $major->id) }}"
            class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Tambah Mitra Perusahaan') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Pilih perusahaan yang sudah ada untuk dijadikan mitra jurusan ini.') }}
            </p>

            <div class="mt-6 space-y-6">
                <!-- Company Select -->
                <div>
                    <x-input-label for="company_id" value="{{ __('Perusahaan') }}" />
                    <select id="company_id" name="company_id"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        required>
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach ($allCompanies as $comp)
                            <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('company_id')" />
                </div>

                <!-- Type -->
                <div>
                    <x-input-label for="partnership_type" value="{{ __('Jenis Kemitraan') }}" />
                    <x-text-input id="partnership_type" name="partnership_type" type="text"
                        class="mt-1 block w-full" placeholder="Contoh: Magang, Kunjungan Industri" />
                    <x-input-error class="mt-2" :messages="$errors->get('partnership_type')" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Start Date -->
                    <div>
                        <x-input-label for="start_date" value="{{ __('Tanggal Mulai') }}" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                    </div>
                    <!-- End Date -->
                    <div>
                        <x-input-label for="end_date" value="{{ __('Tanggal Selesai') }}" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" value="{{ __('Status') }}" />
                    <select id="status" name="status"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="active">Aktif</option>
                        <option value="pending">Menunggu Konfirmasi</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>

                <!-- Document Link -->
                <div>
                    <x-input-label for="document_link" value="{{ __('Link Dokumen (MoU/MoA)') }}" />
                    <x-text-input id="document_link" name="document_link" type="url" class="mt-1 block w-full"
                        placeholder="https://..." />
                    <x-input-error class="mt-2" :messages="$errors->get('document_link')" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Role Assignment Modal -->
    <x-modal name="role-assignment-modal" focusable>
        <form method="post" action="{{ route('admin.majors.role-assignment.update', $major->id) }}"
            class="p-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="role_type" x-model="roleType">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Atur Penugasan') }}: <span x-text="roleLabel"></span>
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Pilih guru untuk posisi ini. Hanya guru yang eligible (tidak memiliki tugas rangkap di jurusan lain) yang ditampilkan.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="teacher_id" value="{{ __('Pilih Guru') }}" />
                <select id="teacher_id" name="teacher_id"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">-- Kosongkan Posisi --</option>
                    @foreach ($eligibleTeachers as $teacher)
                        <option value="{{ $teacher->id }}">
                            {{ $teacher->user->name }} ({{ $teacher->code }})
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('teacher_id')" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
