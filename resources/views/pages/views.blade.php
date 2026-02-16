<x-guest-layout title="Views" description="Jelajahi ruangan, cari siswa, dan cari guru">
    <section class="pt-28 pb-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-5xl mx-auto">
            {{-- Hero --}}
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-medium border border-slate-200 dark:border-slate-700 mb-6">
                    <x-heroicon-o-eye class="w-3.5 h-3.5" />
                    Mode Publik
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Selamat Datang di SpaceLab
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                    Platform digital untuk mengelola dan memantau ruangan, jadwal, guru, dan siswa secara real-time.
                </p>
            </div>

            {{-- Action Cards --}}
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Rooms --}}
                <a href="{{ route('views.rooms') }}"
                    class="group relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-8 hover:border-slate-400 dark:hover:border-slate-600 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-6 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 transition-colors">
                        <x-heroicon-o-building-office-2 class="w-7 h-7 text-slate-700 dark:text-slate-300" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Lihat Ruangan</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                        Jelajahi semua ruangan & gedung, lihat jadwal penggunaan dan status ketersediaan saat ini.
                    </p>
                    <span
                        class="inline-flex items-center text-sm font-medium text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                        Jelajahi
                        <x-heroicon-o-arrow-right class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>

                {{-- Search Student --}}
                <a href="{{ route('views.search-student') }}"
                    class="group relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-8 hover:border-slate-400 dark:hover:border-slate-600 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-6 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 transition-colors">
                        <x-heroicon-o-user-group class="w-7 h-7 text-slate-700 dark:text-slate-300" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Cari Siswa</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                        Temukan siswa berdasarkan nama atau NIS, lihat lokasi ruangan dan jadwal pelajarannya saat ini.
                    </p>
                    <span
                        class="inline-flex items-center text-sm font-medium text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                        Cari Siswa
                        <x-heroicon-o-arrow-right class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>

                {{-- Search Teacher --}}
                <a href="{{ route('views.search-teacher') }}"
                    class="group relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 p-8 hover:border-slate-400 dark:hover:border-slate-600 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-6 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 transition-colors">
                        <x-heroicon-o-academic-cap class="w-7 h-7 text-slate-700 dark:text-slate-300" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Cari Guru</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                        Temukan guru berdasarkan nama atau kode, lihat lokasi mengajar dan jadwal saat ini.
                    </p>
                    <span
                        class="inline-flex items-center text-sm font-medium text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                        Cari Guru
                        <x-heroicon-o-arrow-right class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>
            </div>

            {{-- Bottom info --}}
            <div class="mt-16 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-500">
                    Data ditampilkan berdasarkan jadwal aktif hari ini — {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>
    </section>
</x-guest-layout>
