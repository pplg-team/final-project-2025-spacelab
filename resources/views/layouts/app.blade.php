@props([
    'title' => config('app.name'),
    'description' => 'Halaman default tanpa deskripsi'
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.meta.guest-head')
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    x-data="{
        sidebarOpen: false,
        checkScreen() {
            if (window.innerWidth >= 1024) {
                this.sidebarOpen = true;
            } else {
                this.sidebarOpen = false;
            }
        }
    }"
    x-init="checkScreen(); window.addEventListener('resize', () => checkScreen())"
    class="h-screen bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-100 font-sans flex overflow-hidden"
>
    <!-- Sidebar -->
    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-full"
        class="fixed lg:static inset-y-0 left-0 w-64 border-e border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900 flex flex-col z-50 transition-all duration-200"
    >
        <!-- Sidebar scroll area -->
        <div class="flex-1 overflow-y-auto">
            <!-- Close button (mobile only) -->
            <div class="flex justify-end lg:hidden p-4">
                <x-heroicon-o-x-mark class="w-6 h-6 text-accent cursor-pointer" @click="sidebarOpen = false" />
            </div>

            <!-- Logo -->
            <div class="flex items-center justify-center py-6">
                <a href="{{ route(Auth::user()->role->lower_name . '.index') }}" class="flex items-center space-x-2">
                    <x-application-logo class="h-10 w-auto" />
                    <span class="text-lg font-semibold">SpaceLab</span>
                </a>
            </div>

            <!-- Navigation -->
            @include('partials.auth.sidebar')
        </div>

        <!-- User dropdown -->
        <div class="border-t border-slate-200 dark:border-slate-700 p-4 transition-all duration-200">
            <div class="flex items-center gap-3">
                    @php
                        $user = Auth::user();

                        $avatar = null;

                        if (Auth::user()->role->lower_name === 'siswa') {
                            $avatar = optional($user->student)->avatar;
                        } elseif (Auth::user()->role->lower_name === 'guru') {
                            $avatar = optional($user->teacher)->avatar;
                        }

                        $avatarUrl = $avatar
                            ? Storage::url($avatar)
                            : asset('assets/images/avatar/default-profile.png');
                    @endphp

                    <div class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg">
                        <img
                            src="{{ $avatarUrl }}"
                            alt="{{ $user->name }}"
                            class="h-full w-full object-cover"
                        />
                    </div>

                <div class="flex-1">
                    <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 transition-all duration-200">{{ auth()->user()->email }}</div>
                </div>

                <details class="relative group">
                    <summary class="cursor-pointer list-none">
                        <x-heroicon-o-chevron-up-down class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-all duration-200" />
                    </summary>
                    <ul class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-slate-800 shadow-lg rounded-md border border-gray-200 dark:border-slate-700 transition-all duration-200">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                                <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                                {{ __('Settings') }}
                            </a>
                        </li>
                        <li class="border-t border-gray-200 dark:border-slate-700 transition-all duration-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                                    <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5" />
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </details>
            </div>
        </div>
    </aside>

    <!-- Overlay (mobile only) -->
    <div
        x-show="sidebarOpen && window.innerWidth < 1024"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
    ></div>

    <!-- Main content -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto overflow-x-hidden">
        <!-- Header (mobile) -->
        <header class="lg:hidden flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 transition-all duration-200">
            <div>
                <p>Selamat Datang {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative flex h-8 w-8 overflow-hidden rounded-lg bg-neutral-200 dark:bg-neutral-700 transition-all duration-200">
                    <span class="flex h-full w-full items-center justify-center text-black dark:text-white transition-all duration-200">
                        {{ auth()->user()->initials() }}
                    </span>
                </div>
                <details class="relative group">
                    <summary class="cursor-pointer list-none">
                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                    </summary>
                    <ul class="absolute top-full right-0 mt-2 w-48 bg-white dark:bg-slate-800 shadow-lg rounded-md border border-gray-200 dark:border-slate-700 z-50 transition-all duration-200">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                                <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                                {{ __('Settings') }}
                            </a>
                        </li>
                        <li class="border-t border-gray-200 dark:border-slate-700 transition-all duration-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                                    <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5" />
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </details>
            </div>
        </header>

        <!-- Topbar -->
        <div class="flex sticky top-0 justify-between items-center py-2 px-4 border-b border-slate-200 dark:border-slate-700 gap-4 bg-neskar-blue-50 dark:bg-slate-900 z-10 transition-all duration-200">
            <button @click="sidebarOpen = true" class="p-2 rounded-md lg:hidden hover:bg-slate-200 dark:hover:bg-slate-800 transition-all duration-200">
                <x-heroicon-o-bars-3 class="w-6 h-6" />
            </button>

            <div class="hidden lg:flex">
                {{ $header ?? '' }}
            </div>

            <div class="flex items-center">
                <!-- Notification -->
                <button class="relative p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200" aria-label="Notifications">
                    <x-heroicon-o-bell class="w-6 h-6 text-gray-800 dark:text-gray-200 transition-all duration-200" />
                    <span class="absolute top-0 right-0 inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                </button>

                <!-- Dark Mode Toggle -->
                <button
                    x-data="{
                        darkMode: localStorage.getItem('dark')
                            ? localStorage.getItem('dark') === 'true'
                            : window.matchMedia('(prefers-color-scheme: dark)').matches
                    }"
                    x-init="$watch('darkMode', value => {
                        document.documentElement.classList.toggle('dark', value);
                        localStorage.setItem('dark', value);
                    });
                    document.documentElement.classList.toggle('dark', darkMode);"
                    @click="darkMode = !darkMode"
                    class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
                    aria-label="Toggle Dark Mode"
                >
                    <template x-if="!darkMode">
                        <x-heroicon-o-moon class="w-6 h-6 text-gray-800 dark:text-gray-200 transition-all duration-200" />
                    </template>
                    <template x-if="darkMode">
                        <x-heroicon-o-sun class="w-6 h-6 text-gray-800 dark:text-gray-200 transition-all duration-200" />
                    </template>
                </button>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 p-6">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
