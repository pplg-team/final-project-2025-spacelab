<nav x-data="{ mobileOpen: false }" @keydown.window.escape="mobileOpen = false"
    class="fixed inset-x-0 top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border-b border-gray-100 dark:border-slate-700">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="/" class="flex items-center space-x-2">
                    <x-application-logo />
                    <span class="text-lg font-semibold">SpaceLab</span>
                </a>
            </div>

            @if (!request()->routeIs('login') && !request()->routeIs('attendance.qr') && !request()->routeIs('views.*'))
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6 text-sm">
                    <a href="#features" class="hover:text-accent transition">Fitur</a>
                    <a href="#how-it-works" class="hover:text-accent transition">Cara Kerja</a>
                    <a href="#benefits" class="hover:text-accent transition">Keunggulan</a>
                    <a href="#faqs" class="hover:text-accent transition">FAQ</a>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center space-x-3 text-sm">

                @if (!request()->routeIs('login'))
                    <!-- Auth Links -->
                    @auth
                        <a href="{{ route(Auth::user()->role->lower_name . '.index') }}"
                        class="hover:text-accent transition px-5 py-1.5 rounded-sm text-sm leading-normal border text-[#1b1b18] dark:text-[#EDEDEC]">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                        class="hover:text-accent transition px-5 py-1.5 rounded-sm text-sm leading-normal border text-[#1b1b18] dark:text-[#EDEDEC]">
                            Masuk
                        </a>
                    @endauth
                @endif

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
                    // Set class sesuai initial value
                    document.documentElement.classList.toggle('dark', darkMode);"
                    @click="darkMode = !darkMode"
                    class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    aria-label="Toggle Dark Mode"
                >
                    <template x-if="!darkMode">
                        <x-heroicon-o-moon class="w-6 h-6 text-gray-800 dark:text-gray-200" />
                    </template>
                    <template x-if="darkMode">
                        <x-heroicon-o-sun class="w-6 h-6 text-gray-800 dark:text-gray-200" />
                    </template>
                </button>

                @if (!request()->routeIs('login'))
                    <!-- Mobile Menu -->
                    <button id="mobile-menu-btn"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen"
                        aria-controls="mobile-menu"
                        class="md:hidden p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        aria-label="Toggle mobile menu"
                    >
                        <template x-if="!mobileOpen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </template>
                        <template x-if="mobileOpen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </template>
                    </button>
                @endif

            </div>
        </div>
    </div>
        @if (!request()->routeIs('login'))
            <div id="mobile-menu" x-cloak x-show="mobileOpen" :aria-hidden="!mobileOpen" x-transition:enter="transition ease-out duration-150"
                @click.away="mobileOpen = false"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="md:hidden bg-white/95 dark:bg-slate-900 border-t border-gray-100 dark:border-slate-700 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="pt-3 pb-4 space-y-1">
                        <a href="#features" @click="mobileOpen = false" class="block px-3 py-2 rounded-md text-base font-medium hover:text-accent">Fitur</a>
                        <a href="#how-it-works" @click="mobileOpen = false" class="block px-3 py-2 rounded-md text-base font-medium hover:text-accent">Cara Kerja</a>
                        <a href="#benefits" @click="mobileOpen = false" class="block px-3 py-2 rounded-md text-base font-medium hover:text-accent">Keunggulan</a>
                        <a href="#faqs" @click="mobileOpen = false" class="block px-3 py-2 rounded-md text-base font-medium hover:text-accent">FAQ</a>
                   </div>
                </div>
            </div>
        @endif
</nav>