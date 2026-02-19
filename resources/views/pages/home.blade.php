<x-guest-layout :title="$title" :description="$description">
    @vite('resources/css/home-animations.css')
    @vite('resources/js/home-interactions.js')

    {{--
        LANDING PAGE PERLU DIPERBARUI KARENA FITUR YANG ADA DI APLIKASI SUDAH JAUH LEBIH LENGKAP DAN MENCAKUP JAUH LEBIH BANYAK HAL
     --}}
    <div>
        <!-- HERO -->
        <section class="relative py-60 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900 overflow-hidden">
            <!-- Floating Background Shapes -->
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
            </div>

            <div class="relative max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center z-10">
                <!-- Text Content -->
                <div class="space-y-6">
                    <div
                        class="hero-badge inline-block px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded text-xs font-medium border border-slate-200 dark:border-slate-700">
                        Digitalisasi Manajemen Sekolah
                    </div>
                    <h1 class="hero-title text-4xl md:text-5xl font-bold leading-tight text-slate-900 dark:text-white">
                        <span class="typing-text">Kelola Jadwal Sekolah</span><br>
                        dengan <span class="gradient-text">Lebih Efisien</span>
                    </h1>
                    <p class="hero-description text-lg text-slate-600 dark:text-slate-400 max-w-xl leading-relaxed">
                        Platform terpadu untuk mengelola jadwal pelajaran, ruangan, guru, dan siswa —
                        tanpa konflik jadwal, tanpa kerumitan manual, tanpa kesalahan administrasi.
                    </p>
                    <div class="hero-cta flex flex-col sm:flex-row gap-4 mt-8">
                        <a href="/login"
                            class="btn-primary ripple-effect magnetic-button px-6 py-3 bg-slate-800 dark:bg-slate-700 text-white rounded inline-block text-center hover:bg-slate-900 dark:hover:bg-slate-600 transition">
                            Masuk Sistem
                        </a>
                        <a href="{{ route('views.views') }}"
                            class="btn-secondary ripple-effect px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded inline-block text-center hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Jelajahi Sistem
                        </a>
                    </div>
                </div>

                <!-- Image Preview -->
                <div class="hero-image relative">
                    <img src="{{ asset('assets/images/pages/neskar-ats.webp') }}" alt="Dashboard Sekolah SpaceLab"
                        class="float-animation lazy-image rounded shadow-lg border border-slate-200 dark:border-slate-700 w-full glow-on-hover">
                </div>
            </div>
        </section>

        <!-- PROBLEM STATEMENT -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-slate-950">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        Tantangan dalam Manajemen Sekolah
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                        Sekolah modern menghadapi kompleksitas dalam mengatur jadwal, ruangan, dan sumber daya
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-10 h-10 bg-slate-200 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Konflik Jadwal</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Guru mengajar di dua kelas sekaligus, ruangan terpakai ganda, siswa kehilangan jam pelajaran
                            — semua karena penjadwalan manual yang rentan error.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up stagger-1 glare-card spotlight-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-10 h-10 bg-slate-200 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-clock class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Proses yang Lama</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Penyusunan jadwal memakan waktu berhari-hari bahkan berminggu-minggu, dengan revisi
                            berkali-kali yang menguras tenaga dan waktu staf.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up stagger-2 glare-card spotlight-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-10 h-10 bg-slate-200 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-document-text class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Data Tidak Terintegrasi</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Data tersebar di berbagai file Excel dan dokumen manual, sulit dilacak, rentan hilang, dan
                            tidak ada sinkronisasi yang baik.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SOLUTION OVERVIEW -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        Solusi SpaceLab untuk Sekolah Anda
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                        Sistem terpadu yang mengotomasi dan menyederhanakan seluruh aspek manajemen akademik
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <div class="scroll-reveal fade-left flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="number-badge w-12 h-12 bg-slate-800 dark:bg-slate-700 text-white rounded-lg flex items-center justify-center font-bold counter"
                                data-target="1">
                                0
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Deteksi Konflik
                                Otomatis</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Sistem secara otomatis mendeteksi dan mencegah bentrokan jadwal guru, ruangan, atau
                                siswa sebelum disimpan — tidak ada lagi kesalahan manual.
                            </p>
                        </div>
                    </div>

                    <div class="scroll-reveal fade-right flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="number-badge w-12 h-12 bg-slate-800 dark:bg-slate-700 text-white rounded-lg flex items-center justify-center font-bold counter"
                                data-target="2">
                                0
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Penjadwalan yang Cepat
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Buat dan edit jadwal dalam hitungan menit, bukan hari. Template dan sistem drag-and-drop
                                membuat proses penjadwalan menjadi mudah dan efisien.
                            </p>
                        </div>
                    </div>

                    <div class="scroll-reveal fade-left flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="number-badge w-12 h-12 bg-slate-800 dark:bg-slate-700 text-white rounded-lg flex items-center justify-center font-bold counter"
                                data-target="3">
                                0
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Satu Platform
                                Terintegrasi</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Semua data guru, siswa, ruangan, dan mata pelajaran tersimpan dalam satu sistem yang
                                aman dan mudah diakses oleh pihak yang berwenang.
                            </p>
                        </div>
                    </div>

                    <div class="scroll-reveal fade-right flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="number-badge w-12 h-12 bg-slate-800 dark:bg-slate-700 text-white rounded-lg flex items-center justify-center font-bold counter"
                                data-target="4">
                                0
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Monitoring Real-time
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Pantau penggunaan ruangan, beban mengajar guru, dan aktivitas sekolah secara langsung
                                melalui dashboard yang informatif dan mudah dipahami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-slate-950">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">Fitur Lengkap
                        SpaceLab</h2>
                    <p class="text-slate-600 dark:text-slate-400">Dirancang khusus untuk memenuhi kebutuhan operasional
                        sekolah modern</p>
                </div>

                <div class="spotlight-group grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card feature-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-calendar class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Manajemen Jadwal</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Buat, edit, dan kelola jadwal pelajaran dengan antarmuka intuitif. Sistem otomatis
                            mendeteksi konflik waktu, ruangan, dan ketersediaan guru untuk menghindari bentrokan.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card feature-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-building-office class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Monitoring Ruangan</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Pantau ketersediaan dan penggunaan ruangan secara real-time. Lihat riwayat pemakaian, jadwal
                            mendatang, dan optimalkan alokasi ruang belajar sekolah Anda.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card feature-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-users class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Data Guru & Siswa</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Kelola data lengkap guru dan siswa dalam satu sistem terpusat. Dilengkapi dengan kontrol
                            akses berbasis peran untuk menjaga keamanan dan privasi data.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card feature-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Laporan & Analitik</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Akses laporan komprehensif tentang beban mengajar guru, utilisasi ruangan, dan statistik
                            pembelajaran. Visualisasi data membantu pengambilan keputusan yang lebih baik.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card feature-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-bell class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Notifikasi Otomatis</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Terima pemberitahuan langsung untuk perubahan jadwal, konflik yang terdeteksi, atau
                            informasi penting lainnya. Semua stakeholder tetap terinformasi dengan baik.
                        </p>
                    </div>

                    <div
                        class="scroll-reveal fade-up glare-card spotlight-card feature-card p-6 bg-slate-50 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-800">
                        <div class="glare-overlay"></div>
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-lock-closed class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Keamanan Data</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Data sekolah terlindungi dengan enkripsi modern, autentikasi berlapis, dan backup otomatis.
                            Sistem mengikuti standar keamanan industri terkini.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- USE CASES -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        Dirancang untuk Semua Peran di Sekolah
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400">
                        Setiap pengguna mendapatkan akses dan fitur sesuai kebutuhan mereka
                    </p>
                </div>

                <div class="space-y-6">
                    <div
                        class="scroll-reveal fade-left feature-card bg-white dark:bg-slate-950 p-6 rounded border border-slate-200 dark:border-slate-800">
                        <div class="flex items-start gap-4">
                            <div class="feature-icon bg-slate-100 dark:bg-slate-900 p-3 rounded">
                                <x-heroicon-o-user-circle class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Kepala Sekolah
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                                    Akses dashboard komprehensif untuk monitoring seluruh operasional sekolah, lihat
                                    laporan utilisasi ruangan, beban mengajar guru, dan statistik pembelajaran dalam
                                    satu tampilan.
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Dashboard
                                        Eksekutif</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Laporan
                                        Lengkap</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Monitoring
                                        Real-time</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-left feature-card bg-white dark:bg-slate-950 p-6 rounded border border-slate-200 dark:border-slate-800">
                        <div class="flex items-start gap-4">
                            <div class="feature-icon bg-slate-100 dark:bg-slate-900 p-3 rounded">
                                <x-heroicon-o-academic-cap class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Wakil Kepala
                                    Sekolah / Kurikulum</h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                                    Kelola jadwal pelajaran dengan mudah, atur pembagian kelas dan ruangan, serta
                                    pastikan tidak ada konflik jadwal antara guru, siswa, dan fasilitas sekolah.
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Penjadwalan</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Manajemen
                                        Kurikulum</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Alokasi
                                        Ruangan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-left feature-card bg-white dark:bg-slate-950 p-6 rounded border border-slate-200 dark:border-slate-800">
                        <div class="flex items-start gap-4">
                            <div class="feature-icon bg-slate-100 dark:bg-slate-900 p-3 rounded">
                                <x-heroicon-o-clipboard-document-list
                                    class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Staff Tata Usaha
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                                    Kelola data guru, siswa, dan kelas dengan efisien. Input data secara massal, cetak
                                    laporan administrasi, dan akses riwayat data dengan cepat dan terstruktur.
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Data
                                        Management</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Import/Export</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Laporan
                                        Administratif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-left feature-card bg-white dark:bg-slate-950 p-6 rounded border border-slate-200 dark:border-slate-800">
                        <div class="flex items-start gap-4">
                            <div class="feature-icon bg-slate-100 dark:bg-slate-900 p-3 rounded">
                                <x-heroicon-o-book-open class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg mb-2 text-slate-900 dark:text-white">Guru</h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                                    Lihat jadwal mengajar pribadi, lokasi ruangan, dan daftar siswa di setiap kelas.
                                    Akses informasi jadwal secara real-time dari perangkat apapun, kapan saja.
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Jadwal
                                        Mengajar</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Data
                                        Kelas</span>
                                    <span
                                        class="text-xs bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-800">Akses
                                        Mobile</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section id="how-it-works" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-slate-950">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">Cara Kerja SpaceLab
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400">Tiga langkah sederhana untuk memulai digitalisasi
                        manajemen sekolah</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="scroll-reveal scale-in text-center">
                        <div
                            class="step-number w-16 h-16 bg-slate-800 dark:bg-slate-700 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            1
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-slate-900 dark:text-white">Input Data Awal</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Masukkan data guru, siswa, ruangan, dan mata pelajaran ke dalam sistem. Data dapat di-import
                            secara massal dari file Excel untuk mempercepat proses setup awal.
                        </p>
                    </div>

                    <div class="scroll-reveal scale-in stagger-1 text-center">
                        <div
                            class="step-number w-16 h-16 bg-slate-800 dark:bg-slate-700 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            2
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-slate-900 dark:text-white">Susun Jadwal</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Buat jadwal pelajaran dengan antarmuka yang intuitif. Sistem akan otomatis mendeteksi dan
                            mencegah konflik jadwal, sehingga Anda bisa menyusun jadwal dengan cepat dan akurat.
                        </p>
                    </div>

                    <div class="scroll-reveal scale-in stagger-2 text-center">
                        <div
                            class="step-number w-16 h-16 bg-slate-800 dark:bg-slate-700 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            3
                        </div>
                        <h3 class="text-xl font-semibold mb-3 text-slate-900 dark:text-white">Monitor & Kelola</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Pantau aktivitas sekolah secara real-time, akses laporan kapan saja, dan kelola perubahan
                            jadwal dengan mudah. Dashboard interaktif memberikan insights yang Anda butuhkan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- BENEFITS -->
        <section id="benefits" class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-6">
                            Manfaat Nyata untuk Sekolah Anda
                        </h2>
                        <div class="space-y-6">
                            <div class="scroll-reveal fade-left benefit-item flex gap-3">
                                <div
                                    class="benefit-check w-6 h-6 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <x-heroicon-o-check class="w-4 h-4 text-slate-700 dark:text-slate-300" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Hemat Waktu hingga
                                        70%</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Penyusunan jadwal yang biasanya memakan waktu berminggu-minggu kini bisa
                                        diselesaikan dalam hitungan jam saja dengan sistem otomasi SpaceLab.
                                    </p>
                                </div>
                            </div>

                            <div class="scroll-reveal fade-left benefit-item flex gap-3">
                                <div
                                    class="benefit-check w-6 h-6 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <x-heroicon-o-check class="w-4 h-4 text-slate-700 dark:text-slate-300" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Eliminasi Konflik
                                        Jadwal</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Sistem deteksi otomatis memastikan tidak ada jadwal yang bentrok antara guru,
                                        siswa, atau ruangan — menghilangkan masalah klasik scheduling.
                                    </p>
                                </div>
                            </div>

                            <div class="scroll-reveal fade-left benefit-item flex gap-3">
                                <div
                                    class="benefit-check w-6 h-6 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <x-heroicon-o-check class="w-4 h-4 text-slate-700 dark:text-slate-300" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Transparansi Penuh
                                    </h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Semua pihak dapat mengakses informasi yang relevan sesuai dengan peran mereka,
                                        meningkatkan koordinasi dan komunikasi di lingkungan sekolah.
                                    </p>
                                </div>
                            </div>

                            <div class="scroll-reveal fade-left benefit-item flex gap-3">
                                <div
                                    class="benefit-check w-6 h-6 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <x-heroicon-o-check class="w-4 h-4 text-slate-700 dark:text-slate-300" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Akses Fleksibel</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Platform berbasis web yang responsif memungkinkan akses dari desktop, tablet,
                                        maupun smartphone — bekerja dari mana saja, kapan saja.
                                    </p>
                                </div>
                            </div>

                            <div class="scroll-reveal fade-left benefit-item flex gap-3">
                                <div
                                    class="benefit-check w-6 h-6 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <x-heroicon-o-check class="w-4 h-4 text-slate-700 dark:text-slate-300" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Pengambilan Keputusan
                                        Berbasis Data</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Laporan dan analitik yang komprehensif memberikan insights untuk optimalisasi
                                        sumber daya dan peningkatan efisiensi operasional sekolah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-right bg-white dark:bg-slate-950 rounded p-8 border border-slate-200 dark:border-slate-800">
                        <div class="space-y-6">
                            <div
                                class="scroll-reveal fade-left testimonial-card border-l-4 border-slate-400 dark:border-slate-600 pl-4">
                                <p class="text-slate-700 dark:text-slate-300 italic mb-3 leading-relaxed">
                                    "SpaceLab sangat membantu kami dalam mengelola 45 kelas dan 80 guru. Tidak ada lagi
                                    masalah ruangan dobel atau guru mengajar di dua tempat sekaligus. Penyusunan jadwal
                                    yang dulu bisa sampai 2 minggu, sekarang hanya butuh 2 hari."
                                </p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Drs. Ahmad Wijaya, M.Pd
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Kepala Sekolah SMA Negeri 5</p>
                            </div>

                            <div
                                class="scroll-reveal fade-left testimonial-card border-l-4 border-slate-400 dark:border-slate-600 pl-4">
                                <p class="text-slate-700 dark:text-slate-300 italic mb-3 leading-relaxed">
                                    "Dengan SpaceLab, kami bisa memantau penggunaan ruangan secara real-time. Efisiensi
                                    penggunaan fasilitas meningkat signifikan. Ruang laboratorium dan workshop yang dulu
                                    sering kosong kini terpakai optimal."
                                </p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Dr. Siti Nurhaliza,
                                    S.Pd, M.M</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Wakil Kepala Sekolah SMK Telkom
                                </p>
                            </div>

                            <div
                                class="scroll-reveal fade-left testimonial-card border-l-4 border-slate-400 dark:border-slate-600 pl-4">
                                <p class="text-slate-700 dark:text-slate-300 italic mb-3 leading-relaxed">
                                    "Interface yang sederhana membuat staff kami yang tidak terlalu paham teknologi bisa
                                    langsung menggunakan sistem ini. Pelatihan singkat sudah cukup untuk mereka bisa
                                    produktif."
                                </p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Budi Santoso, S.Kom</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Koordinator TI SMP Muhammadiyah
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TRUST INDICATORS -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-slate-950">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        Sistem yang Dapat Diandalkan
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400">
                        Komitmen kami untuk keamanan, reliabilitas, dan dukungan terbaik
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="scroll-reveal scale-in text-center p-6">
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <x-heroicon-o-shield-check class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Keamanan Terjamin</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Data terenkripsi, backup otomatis, dan proteksi berlapis untuk melindungi informasi sekolah
                            Anda dari ancaman keamanan.
                        </p>
                    </div>

                    <div class="scroll-reveal scale-in stagger-1 text-center p-6">
                        <div
                            class="feature-icon w-12 h-12 bg-slate-100 dark:bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <x-heroicon-o-arrow-path class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Uptime 99.9%</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Infrastruktur yang handal dengan tingkat ketersediaan tinggi memastikan sistem selalu dapat
                            diakses saat Anda membutuhkannya.
                        </p>
                    </div>

                    <div class="text-center p-6">
                        <div
                            class="w-12 h-12 bg-slate-100 dark:bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 text-slate-700 dark:text-slate-300" />
                        </div>
                        <h3 class="font-semibold mb-2 text-slate-900 dark:text-white">Dukungan Responsif</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Tim support kami siap membantu dengan pelatihan, troubleshooting, dan pendampingan
                            implementasi sistem di sekolah Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faqs" class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">Pertanyaan yang
                        Sering Diajukan</h2>
                    <p class="text-slate-600 dark:text-slate-400">Temukan jawaban atas pertanyaan umum tentang SpaceLab
                    </p>
                </div>

                <div class="space-y-4">
                    <div
                        class="scroll-reveal fade-up faq-item border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-950">
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Apakah SpaceLab cocok untuk
                                semua jenis sekolah?</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Ya, SpaceLab dirancang untuk semua jenjang pendidikan mulai dari SD, SMP, SMA, hingga
                                SMK. Sistem dapat disesuaikan dengan kebutuhan spesifik setiap institusi, baik sekolah
                                negeri maupun swasta, dengan skala kecil hingga besar.
                            </p>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-up faq-item border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-950">
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Bagaimana sistem mendeteksi
                                konflik jadwal?</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Sistem secara otomatis memeriksa ketersediaan guru, ruangan, dan kelas saat jadwal
                                dibuat atau diubah. Jika terdeteksi konflik (misalnya guru mengajar di dua tempat di
                                waktu yang sama), sistem akan memberikan peringatan dan mencegah penyimpanan jadwal
                                tersebut.
                            </p>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-up faq-item border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-950">
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Berapa lama waktu yang
                                dibutuhkan untuk implementasi?</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Implementasi awal biasanya memakan waktu 1-2 minggu, termasuk setup sistem, migrasi
                                data, dan pelatihan pengguna. Tim kami akan mendampingi sekolah hingga sistem berjalan
                                lancar dan staff mahir menggunakannya.
                            </p>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-up faq-item border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-950">
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Apakah data sekolah aman di
                                SpaceLab?</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Sangat aman. Kami menggunakan enkripsi data end-to-end, autentikasi berlapis, backup
                                otomatis harian, dan sistem keamanan yang mengikuti standar industri. Data hanya dapat
                                diakses oleh pihak yang memiliki autoritas sesuai peran mereka.
                            </p>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-up faq-item border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-950">
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Apakah bisa mengimpor data
                                yang sudah ada?</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Ya, SpaceLab mendukung import data massal dari file Excel/CSV. Anda dapat mengimpor data
                                guru, siswa, mata pelajaran, dan ruangan sekaligus untuk mempercepat proses setup awal
                                tanpa perlu input manual satu per satu.
                            </p>
                        </div>
                    </div>

                    <div
                        class="scroll-reveal fade-up faq-item border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-950">
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Apakah ada pelatihan untuk
                                pengguna?</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Ya, kami menyediakan pelatihan komprehensif untuk semua pengguna sesuai peran mereka.
                                Pelatihan mencakup sesi hands-on, dokumentasi lengkap, dan video tutorial. Support
                                berkelanjutan juga tersedia untuk memastikan transisi yang mulus.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-slate-800 dark:bg-slate-950 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Transformasi Manajemen Sekolah Anda?</h2>
                <p class="text-slate-300 dark:text-slate-400 mb-8 text-lg leading-relaxed">
                    Bergabunglah dengan ratusan sekolah yang telah merasakan manfaat efisiensi dan transparansi dengan
                    SpaceLab
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/login"
                        class="btn-primary ripple-effect magnetic-button px-8 py-3 bg-white text-slate-900 rounded font-semibold hover:bg-slate-100 transition inline-block">
                        Masuk ke Sistem
                    </a>
                    <a href="#features"
                        class="btn-secondary ripple-effect px-8 py-3 border-2 border-white text-white rounded font-semibold hover:bg-white hover:text-slate-900 transition inline-block">
                        Pelajari Fitur
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
