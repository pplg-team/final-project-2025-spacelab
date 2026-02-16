<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Jadwal') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8">

            {{-- Statistik Dashboard --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Card: Total Jadwal -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Jadwal') }}</p>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-200 mt-2">42</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Tersebar di semua kelas') }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Jadwal Aktif Hari Ini -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Jadwal Hari Ini') }}</p>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-200 mt-2">8</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Jam pelajaran') }}</p>
                            </div>
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Total Kelas -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Kelas') }}</p>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-200 mt-2">12</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('dengan jadwal') }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.5m0 0H9m11 0v-3.5a6.5 6.5 0 00-13 0V21"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Total Ruangan Aktif -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Ruangan') }}</p>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-200 mt-2">15</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('dalam penggunaan') }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m2-2l6.406-2.134A2 2 0 0112 5v6m0 0l6.406 2.134A2 2 0 0021 13v6m-14-4v4m0 0h14m-14 0H5m14 0h2m-2-4h2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pilihan Navigasi Manajemen Jadwal --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-16">{{ __('Kelola Jadwal') }}</h3>
                <div class="flex justify-center gap-6 flex-wrap">
                    <!-- Manage Class Schedules -->
                    <a href="{{ route('admin.schedules.timetable.index') }}" 
                       class="group bg-white dark:bg-gray-800 hover:shadow-lg hover:shadow-blue-200 dark:hover:shadow-blue-900 transition-shadow overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-600">
                        <div class="px-6 py-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        {{ __('Jadwal Pelajaran') }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ __('Kelola jadwal pelajaran untuk setiap kelas') }}
                                    </p>
                                    <span class="inline-flex items-center text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        {{ __('Lihat Jadwal') }}
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg ml-4">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747C22 10.998 17.5 6.253 12 6.253z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Room Schedules -->
                    <a href="#" 
                       class="group bg-white dark:bg-gray-800 hover:shadow-lg hover:shadow-green-200 dark:hover:shadow-green-900 transition-shadow overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-600">
                        <div class="px-6 py-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        {{ __('Jadwal Ruangan') }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ __('Kelola penggunaan ruangan dan kapasitasnya') }}
                                    </p>
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        {{ __('Lihat Jadwal') }}
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg ml-4">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m2-2l6.406-2.134A2 2 0 0112 5v6m0 0l6.406 2.134A2 2 0 0121 13v6m-14-4v4m0 0h14m-14 0H5m14 0h2m-2-4h2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Check Conflicts -->
                    {{-- <a href="{{ route('schedules.conflicts') }}" 
                       class="group bg-white dark:bg-gray-800 hover:shadow-lg hover:shadow-red-200 dark:hover:shadow-red-900 transition-shadow overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-600">
                        <div class="px-6 py-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        {{ __('Cek Konflik Jadwal') }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ __('Deteksi dan atasi bentrokan jadwal') }}
                                    </p>
                                    <span class="inline-flex items-center text-red-600 dark:text-red-400 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        {{ __('Kelola') }}
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg ml-4">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6.63 6.63a9 9 0 119.99 9.99"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a> --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>