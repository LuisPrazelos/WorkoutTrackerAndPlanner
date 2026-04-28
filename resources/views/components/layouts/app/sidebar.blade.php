<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 lg:flex">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>Dashboard</flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('schemas.index')" :current="request()->routeIs('schemas.index')" wire:navigate>Schema Bibliotheek</flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" :href="route('workout-log')" :current="request()->routeIs('workout-log')" wire:navigate>Workout Logboek</flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" :href="route('stats')" :current="request()->routeIs('stats')" wire:navigate>Statistieken</flux:navlist.item>
                    <flux:navlist.item icon="plus-circle" :href="route('workout-schemas.create')" :current="request()->routeIs('workout-schemas.create')" wire:navigate>Nieuw Schema</flux:navlist.item>

                    @if(Auth::user()->isAdmin())
                    <flux:navlist.group heading="Admin" class="grid mt-2">
                        <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>Beheer Gebruikers</flux:navlist.item>
                    </flux:navlist.group>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <div class="mt-auto px-4 pb-4">
                <button id="theme-toggle" type="button" class="flex gap-3 items-center w-full px-3 py-2 text-sm font-medium text-zinc-600 transition-colors rounded-lg hover:bg-zinc-200/50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                    <flux:icon name="moon" class="w-5 h-5 block dark:hidden" />
                    <flux:icon name="sun" class="w-5 h-5 hidden dark:block" />
                    <span class="block dark:hidden">Donkere Modus</span>
                    <span class="hidden dark:block">Lichte Modus</span>
                </button>
            </div>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <div class="flex-1 flex flex-col">
            @if (session('success'))
                <div class="bg-green-500 text-white text-center p-2">
                    {{ session('success') }}
                </div>
            @endif
            {{ $slot }}
        </div>

        @fluxScripts
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const themeToggleBtn = document.getElementById('theme-toggle');
                const themeKey = 'workout_tracker_theme_v2';
                if (!themeToggleBtn) return;

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

                const currentTheme = localStorage.getItem(themeKey) || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                applyTheme(currentTheme);

                themeToggleBtn.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';
                    localStorage.setItem(themeKey, newTheme);
                    applyTheme(newTheme);
                });
            });
        </script>
    </body>
</html>
