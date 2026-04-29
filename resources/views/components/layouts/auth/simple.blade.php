<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script>
            (function() {
                const key = 'workout_tracker_theme_v2';
                const saved = localStorage.getItem(key) ?? localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
        @include('partials.head')
        <style>
            .glass-panel {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(0, 0, 0, 0.1);
            }
            .dark .glass-panel {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .auth-bg {
                background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent 40%),
                            radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.1), transparent 40%),
                            #fafafa;
            }
            .dark .auth-bg {
                background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent 40%),
                            radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.1), transparent 40%),
                            #09090b;
            }
        </style>
    </head>
    <body class="min-h-screen auth-bg antialiased text-zinc-900 dark:text-white selection:bg-indigo-500/30 selection:text-indigo-200 transition-colors duration-300">
        <div class="flex min-h-svh flex-col items-center justify-center gap-8 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-8">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-4 group" wire:navigate>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/10 border border-indigo-500/20 group-hover:border-indigo-500/50 transition-all duration-300">
                        <x-app-logo-icon class="size-8 text-indigo-500" />
                    </div>
                    <span class="text-xl font-bold tracking-tight font-heading group-hover:text-indigo-400 transition-colors uppercase italic">{{ config('app.name', 'Laravel') }}</span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="glass-panel p-8 rounded-[32px] shadow-2xl shadow-black/50">
                    {{ $slot }}
                </div>
                
                <p class="text-center text-xs text-zinc-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Gebouwd voor resultaten.
                </p>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
