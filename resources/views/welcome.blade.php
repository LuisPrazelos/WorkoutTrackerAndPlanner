<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => 'Transformeer Je Training'])
        <style>
            .glass-effect {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .gradient-text {
                background: linear-gradient(to right, #6366f1, #a855f7);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .hero-gradient {
                background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent),
                            radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.15), transparent);
            }
        </style>
    </head>
    <body class="antialiased bg-white dark:bg-zinc-950 text-zinc-900 dark:text-white font-sans selection:bg-indigo-500/30 selection:text-indigo-200 transition-colors duration-300">
        <div class="relative min-h-screen hero-gradient overflow-hidden">
            <!-- Navigatie -->
            <nav class="relative z-50 flex items-center justify-between px-6 py-8 mx-auto max-w-7xl lg:px-8">
                <div class="flex items-center gap-2">
                    <x-app-logo-icon class="size-10 text-indigo-500" />
                    <span class="text-xl font-bold tracking-tight text-white font-heading">{{ config('app.name') }}</span>
                </div>

                <div class="flex items-center gap-4 lg:gap-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium transition-colors hover:text-indigo-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium transition-colors hover:text-indigo-400">Aanmelden</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl transition-all hover:bg-indigo-500 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)] shadow-lg shadow-indigo-600/20 active:scale-95">
                                Registreer Nu
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>

            <!-- Hero Sectie -->
            <div class="relative z-10 pt-16 pb-24 mx-auto max-w-7xl px-6 lg:px-8 lg:pt-32">
                <div class="grid items-center gap-16 lg:grid-cols-2">
                    <div>
                        <div class="inline-flex items-center px-3 py-1 mb-6 text-sm font-medium rounded-full glass-effect text-indigo-300 border-indigo-500/20">
                            <span class="flex size-2 me-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            Nieuw: AI-gestuurde Trainingsplannen
                        </div>
                        <h1 class="text-5xl font-extrabold tracking-tight sm:text-7xl font-heading leading-[1.1]">
                            Zet Je <br />
                            <span class="gradient-text">Visie Om In Resultaten</span>
                        </h1>
                        <p class="mt-8 text-lg leading-8 text-zinc-400 max-w-xl">
                            De ultieme trainingsplanner voor elite-atleten en beginners. Volg elke set, monitor je voortgang en bereik je doelen met onze premium trainingsintelligentie.
                        </p>
                        <div class="flex items-center mt-10 gap-x-6">
                            <a href="{{ route('register') }}" class="px-8 py-4 text-base font-bold text-white bg-vibrant rounded-2xl transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(99,102,241,0.5)]">
                                Begin Je Reis
                            </a>
                            <a href="#features" class="text-sm font-semibold leading-6 transition-colors text-white hover:text-indigo-400 flex items-center gap-2">
                                Bekijk functies <span aria-hidden="true">→</span>
                            </a>
                        </div>

                        <div class="mt-12 flex items-center gap-4 text-sm text-zinc-500">
                            <div class="flex -space-x-2">
                                <img class="size-8 rounded-full border-2 border-zinc-950" src="https://ui-avatars.com/api/?name=User+1&background=6366f1&color=fff" alt="">
                                <img class="size-8 rounded-full border-2 border-zinc-950" src="https://ui-avatars.com/api/?name=User+2&background=a855f7&color=fff" alt="">
                                <img class="size-8 rounded-full border-2 border-zinc-950" src="https://ui-avatars.com/api/?name=User+3&background=3b82f6&color=fff" alt="">
                            </div>
                            <span>Al door 10.000+ atleten wereldwijd gebruikt</span>
                        </div>
                    </div>

                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative overflow-hidden rounded-3xl border border-white/10 glass-effect p-2">
                            <img src="/images/hero.png" alt="Gym Hero" class="rounded-2xl transition duration-500 group-hover:scale-105 object-cover w-full h-[500px]" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Functies Sectie -->
            <div id="features" class="relative z-10 py-24 mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-base font-semibold leading-7 text-cyan-400 uppercase tracking-widest">Premium Functies</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl font-heading uppercase italic">Alles wat je nodig hebt om te slagen</p>
                </div>

                <div class="grid gap-8 lg:grid-cols-3">
                    <!-- Functie 1 -->
                    <div class="p-8 rounded-3xl glass-effect border-white/5 hover:border-indigo-500/30 transition-all duration-300 group">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-400 mb-6 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5-3.75h16.5m-16.5 7.5h16.5" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Slimme Trainingsschema's</h3>
                        <p class="text-zinc-400">Gepersonaliseerde schema's afgestemd op jouw doelen, uitrusting en schema. Gemaakt door experts, uitgevoerd door jou.</p>
                    </div>

                    <!-- Functie 2 -->
                    <div class="p-8 rounded-3xl glass-effect border-white/5 hover:border-purple-500/30 transition-all duration-300 group">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-400 mb-6 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Realtime Bijhouden</h3>
                        <p class="text-zinc-400">Log je sets, reps en volume onderweg. Blijf gefocust op de oefening terwijl wij de data bijhouden.</p>
                    </div>

                    <!-- Functie 3 -->
                    <div class="p-8 rounded-3xl glass-effect border-white/5 hover:border-cyan-500/30 transition-all duration-300 group">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-400 mb-6 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Voortgangsanalyse</h3>
                        <p class="text-zinc-400">Visualiseer je prestaties met mooie grafieken en inzichten. Zie je kracht week na week groeien.</p>
                    </div>
                </div>
            </div>

            <!-- CTA Sectie -->
            <div class="relative z-10 py-24 mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <div class="p-12 lg:p-24 rounded-[40px] bg-vibrant relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 size-64 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 size-64 rounded-full bg-indigo-950/20 blur-3xl"></div>

                    <div class="relative z-10">
                        <h2 class="text-4xl font-extrabold sm:text-5xl font-heading mb-8">Klaar om je training te verslaan?</h2>
                        <a href="{{ route('register') }}" class="px-10 py-5 text-lg font-bold text-indigo-600 bg-white rounded-2xl transition-all hover:scale-105 hover:shadow-2xl">
                            Doe mee met 10.000+ atleten
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="relative z-10 py-12 border-t border-white/5">
                <div class="mx-auto max-w-7xl px-6 lg:px-8 flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="flex items-center gap-2">
                        <x-app-logo-icon class="size-6 text-indigo-500/50" />
                        <span class="text-sm font-semibold tracking-tight text-zinc-500 uppercase">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-xs text-zinc-500">&copy; {{ date('Y') }} {{ config('app.name') }}. Alle rechten voorbehouden.</p>
                    <div class="flex gap-6 text-sm text-zinc-500">
                        <a href="#" class="hover:text-indigo-400 transition-colors">Privacy</a>
                        <a href="#" class="hover:text-indigo-400 transition-colors">Voorwaarden</a>
                    </div>
                </div>
            </footer>
        </div>

        @fluxScripts
    </body>
</html>
