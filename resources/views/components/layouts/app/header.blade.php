<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script>
            (function() {
                const key = 'workout_tracker_theme_v2';
                const saved = localStorage.getItem(key);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
        @include('partials.head')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .glass-header {
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                background: rgba(255, 255, 255, 0.8);
            }
            .dark .glass-header {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                background: rgba(9, 9, 11, 0.8);
            }
            .nav-item-active {
                background: linear-gradient(to right, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
                border-bottom: 2px solid #6366f1;
            }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 antialiased text-zinc-900 dark:text-zinc-100 selection:bg-indigo-500/30 transition-colors duration-300">
        <flux:header sticky class="glass-header z-50 h-16">
            <div class="container mx-auto flex items-center h-full px-4 lg:px-8">
                <!-- Mobile Logo (Always Visible) -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group shrink-0" wire:navigate>
                    <x-app-logo-icon class="size-7" />
                    <span class="text-base font-black tracking-tighter font-heading group-hover:text-indigo-400 transition-colors uppercase italic hidden sm:block">
                        {{ config('app.name') }}
                    </span>
                </a>

                <flux:spacer />

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-1">
                    <flux:navbar class="gap-1">
                        <flux:navbar.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>Dashboard</flux:navbar.item>
                        <flux:navbar.item :href="route('agenda')" :current="request()->routeIs('agenda')" wire:navigate>Agenda</flux:navbar.item>
                        <flux:navbar.item :href="route('schemas.index')" :current="request()->routeIs('schemas.index')" wire:navigate>Bibliotheek</flux:navbar.item>
                        <flux:navbar.item :href="route('workout-log')" :current="request()->routeIs('workout-log')" wire:navigate>Logboek</flux:navbar.item>
                        <flux:navbar.item :href="route('stats')" :current="request()->routeIs('stats')" wire:navigate>Stats</flux:navbar.item>
                        
                        @if(Auth::user()->isAdmin())
                            <flux:navbar.item :href="route('admin.users')" :current="request()->routeIs('admin.users')" class="text-cyan-400 font-bold" wire:navigate>Admin</flux:navbar.item>
                        @endif
                    </flux:navbar>

                    <div class="h-4 w-px bg-zinc-200 dark:bg-zinc-800 mx-4"></div>
                    
                    <a href="{{ route('workout-schemas.create') }}" 
                       class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-vibrant rounded-lg hover:shadow-lg hover:shadow-cyan-500/20 active:scale-95 whitespace-nowrap"
                       wire:navigate>
                        <flux:icon name="plus" class="size-4" />
                        <span>Nieuw Schema</span>
                    </a>
                </nav>

                <flux:spacer class="hidden lg:block" />

                <div class="flex items-center gap-3 shrink-0">
                    <!-- Theme Toggle -->
                    <button id="theme-toggle-header" type="button" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800/50 transition-colors text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                        <flux:icon name="moon" class="size-5 block dark:hidden" />
                        <flux:icon name="sun" class="size-5 hidden dark:block" />
                    </button>

                    <div class="h-4 w-px bg-zinc-200 dark:bg-zinc-800 mx-1"></div>

                    <!-- User Menu -->
                    <flux:dropdown position="bottom" align="end">
                        <flux:profile
                            class="cursor-pointer hover:opacity-80 transition-opacity"
                            :initials="auth()->user()->initials()"
                        />

                        <flux:menu class="w-[240px]">
                            <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-800 mb-2">
                                <p class="text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <flux:menu.item :href="route('settings.profile')" icon="user-circle" wire:navigate>Profielinstellingen</flux:menu.item>
                            <flux:menu.item :href="route('settings.appearance')" icon="swatch" wire:navigate>Weergave</flux:menu.item>
                            <flux:menu.item :href="route('workout-schemas.create')" icon="plus-circle" wire:navigate class="lg:hidden text-indigo-500 font-medium">Nieuw Schema</flux:menu.item>
                            
                            @if(Auth::user()->isAdmin())
                                <flux:menu.item :href="route('admin.users')" icon="shield-check" wire:navigate class="text-cyan-500 font-bold">Admin Hub</flux:menu.item>
                            @endif
                            
                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="text-red-500 hover:text-red-600">
                                    {{ __('Uitloggen') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </flux:header>

        <!-- Mobile Bottom Navigation (Tab Bar) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 glass-header border-t border-zinc-200 dark:border-zinc-800 px-4 pb-safe">
            <div class="flex items-center justify-around h-16">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-zinc-400' }}" wire:navigate>
                    <flux:icon name="home" class="size-5" variant="{{ request()->routeIs('dashboard') ? 'solid' : 'outline' }}" />
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Home</span>
                </a>
                <a href="{{ route('agenda') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('agenda') ? 'text-indigo-500' : 'text-zinc-400' }}" wire:navigate>
                    <flux:icon name="calendar" class="size-5" variant="{{ request()->routeIs('agenda') ? 'solid' : 'outline' }}" />
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Agenda</span>
                </a>
                <a href="{{ route('stats') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('stats') ? 'text-indigo-500' : 'text-zinc-400' }}" wire:navigate>
                    <flux:icon name="chart-bar" class="size-5" variant="{{ request()->routeIs('stats') ? 'solid' : 'outline' }}" />
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Stats</span>
                </a>
                <a href="{{ route('schemas.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('schemas.index') ? 'text-indigo-500' : 'text-zinc-400' }}" wire:navigate>
                    <flux:icon name="book-open" class="size-5" variant="{{ request()->routeIs('schemas.index') ? 'solid' : 'outline' }}" />
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Bibl.</span>
                </a>
                <a href="{{ route('workout-log') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('workout-log') ? 'text-indigo-500' : 'text-zinc-400' }}" wire:navigate>
                    <flux:icon name="clipboard-document-list" class="size-5" variant="{{ request()->routeIs('workout-log') ? 'solid' : 'outline' }}" />
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Logboek</span>
                </a>
            </div>
        </nav>

        <main class="container mx-auto px-4 lg:px-8 py-8 mb-20 lg:mb-0">
            @if (session('success'))
                <div
                    x-data="{ visible: true }"
                    x-init="setTimeout(() => visible = false, 3500)"
                    x-show="visible"
                    x-transition.opacity.duration.250ms
                    class="mb-6 flex items-start justify-between gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-900 shadow-sm dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-100"
                >
                    <div class="flex items-start gap-3">
                        <flux:icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-green-600 dark:text-green-400" />
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button type="button" @click="visible = false" class="text-green-700 transition-colors hover:text-green-900 dark:text-green-300 dark:hover:text-white">
                        <flux:icon name="x-mark" class="size-4" />
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div
                    x-data="{ visible: true }"
                    x-init="setTimeout(() => visible = false, 4500)"
                    x-show="visible"
                    x-transition.opacity.duration.250ms
                    class="mb-6 flex items-start justify-between gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-900 shadow-sm dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-100"
                >
                    <div class="flex items-start gap-3">
                        <flux:icon name="exclamation-circle" class="mt-0.5 size-5 shrink-0 text-red-600 dark:text-red-400" />
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                    <button type="button" @click="visible = false" class="text-red-700 transition-colors hover:text-red-900 dark:text-red-300 dark:hover:text-white">
                        <flux:icon name="x-mark" class="size-4" />
                    </button>
                </div>
            @endif

            {{ $slot }}
        </main>

        @fluxScripts
        <script>
            function initTheme() {
                const themeToggleBtn = document.getElementById('theme-toggle-header');
                if (!themeToggleBtn) return;
                const themeKey = 'workout_tracker_theme_v2';
                
                const applyTheme = (theme) => {
                    document.documentElement.classList.add('theme-transition');

                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }

                    window.clearTimeout(window.__themeTransitionTimeout);
                    window.__themeTransitionTimeout = window.setTimeout(() => {
                        document.documentElement.classList.remove('theme-transition');
                    }, 275);
                };

                const saved = localStorage.getItem(themeKey);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const currentTheme = saved ?? (prefersDark ? 'dark' : 'light');
                applyTheme(currentTheme);

                // Remove existing listener to avoid duplicates if script runs multiple times
                themeToggleBtn.replaceWith(themeToggleBtn.cloneNode(true));
                const newBtn = document.getElementById('theme-toggle-header');

                newBtn.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';
                    localStorage.setItem(themeKey, newTheme);
                    applyTheme(newTheme);
                });
            }

            // Run on initial load
            initTheme();
            // Run on every Livewire navigation
            document.addEventListener('livewire:navigated', initTheme);
        </script>
    </body>
</html>
