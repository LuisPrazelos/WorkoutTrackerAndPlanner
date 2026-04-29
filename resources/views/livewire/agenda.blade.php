<div class="p-4 md:p-6 space-y-6 md:space-y-8 max-w-7xl mx-auto" x-data="{
    showLibrary: false,
    init() {
        if (typeof Sortable !== 'undefined') {
            // Initialize Sidebar
            const sidebars = [this.$refs.sidebarList, this.$refs.mobileSidebarList];
            sidebars.forEach(el => {
                if(el) {
                    new Sortable(el, {
                        group: { name: 'workouts', pull: 'clone', put: false },
                        sort: false,
                        animation: 150,
                        delay: 200, // Touch delay for easier scrolling
                        delayOnTouchOnly: true,
                        ghostClass: 'opacity-50'
                    });
                }
            });

            // Initialize each day as a drop target
            @foreach($days as $day)
                new Sortable(document.getElementById('drop-zone-{{ $day['full'] }}'), {
                    group: 'workouts',
                    animation: 150,
                    ghostClass: 'bg-cyan-500/20',
                    onAdd: (e) => {
                        const schemaId = e.item.getAttribute('data-id');
                        const date = '{{ $day['full'] }}';
                        @this.call('handleDrop', schemaId, date);
                        e.item.remove();
                        this.showLibrary = false;
                    }
                });
            @endforeach
        }
    }
}">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Agenda Column -->
        <div class="lg:col-span-3 space-y-6 md:space-y-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gradient tracking-tight italic uppercase">Training Agenda</h1>
                    <p class="text-zinc-500 mt-1 uppercase text-[10px] font-bold tracking-widest">Smeed je discipline</p>
                </div>
                
                <div class="flex items-center gap-2 bg-zinc-100 dark:bg-zinc-800/50 p-1 rounded-2xl border border-zinc-200 dark:border-zinc-700 w-full sm:w-auto overflow-hidden">
                    <flux:button variant="subtle" icon="chevron-left" wire:click="previousWeek" size="sm" />
                    <span class="flex-1 text-center sm:px-4 text-[11px] md:text-sm font-bold uppercase tracking-tight whitespace-nowrap">
                        Week van {{ \Carbon\Carbon::parse($startOfWeek)->locale('nl')->translatedFormat('d M') }}
                    </span>
                    <flux:button variant="subtle" icon="chevron-right" wire:click="nextWeek" size="sm" />
                </div>
            </div>

            <!-- Scrollable Weekly Strip View -->
            <div class="flex overflow-x-auto pb-4 -mx-1 px-1 gap-3 snap-x no-scrollbar md:grid md:grid-cols-7 md:overflow-visible">
                @foreach($days as $day)
                    <div class="flex-shrink-0 w-20 md:w-auto snap-center space-y-2">
                        <div wire:click="selectDate('{{ $day['full'] }}')" 
                            id="drop-zone-{{ $day['full'] }}"
                            class="flex flex-col items-center justify-center p-3 md:p-4 rounded-2xl transition-all duration-300 relative border-2 w-full aspect-square md:aspect-auto cursor-pointer
                                   {{ $day['isSelected'] ? 'bg-cyan-100 dark:bg-cyan-500/20 border-cyan-400 dark:border-cyan-400 shadow-[0_10px_30px_rgba(34,211,238,0.2)] scale-105 z-10 text-cyan-950 dark:text-white' : 'bg-white dark:bg-zinc-900 border-zinc-100 dark:border-zinc-800 hover:border-indigo-400/50' }}
                                   {{ $day['isToday'] && !$day['isSelected'] ? 'border-cyan-400' : '' }}">
                            
                            <span class="text-[9px] uppercase font-bold tracking-tighter mb-1 {{ $day['isSelected'] ? 'text-cyan-700 dark:text-cyan-200' : 'text-zinc-400' }}">
                                {{ $day['name'] }}
                            </span>
                            <span class="text-xl md:text-2xl font-black {{ $day['isSelected'] ? 'text-cyan-950 dark:text-white' : 'text-zinc-900 dark:text-zinc-100' }}">
                                {{ $day['day'] }}
                            </span>

                            @if($day['hasWorkouts'] && !$day['isSelected'])
                                <span class="absolute bottom-2 size-1.5 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(34,211,238,0.8)]"></span>
                            @endif

                            {{-- Mobile Planning Shortcut --}}
                            <button @click.stop="showLibrary = true; $wire.selectDate('{{ $day['full'] }}')" 
                                    class="absolute top-1 right-1 md:top-2 md:right-2 p-1 md:p-1.5 rounded-full bg-cyan-400 text-white shadow-lg active:scale-95 transition-transform lg:hidden">
                                <flux:icon name="plus" class="size-2.5 md:size-3" />
                            </button>
                        </div>
                        
                        @if($day['isToday'])
                            <p class="text-[8px] font-bold text-center text-cyan-400 uppercase tracking-tighter">Vandaag</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Daily Workouts List -->
            <div class="space-y-4">
                 <div class="flex items-center justify-between">
                    <h3 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white italic uppercase tracking-tight">
                        Focus: {{ \Carbon\Carbon::parse($selectedDate)->locale('nl')->translatedFormat('l d F') }}
                    </h3>
                </div>

                @forelse($scheduledWorkouts as $workout)
                    <div class="glass-panel p-4 md:p-5 border-l-4 border-cyan-400 group relative overflow-hidden transition-all hover:bg-white/10 dark:hover:bg-zinc-800/10 active:scale-[0.98]">
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex-1">
                                <h4 class="text-base md:text-lg font-black text-zinc-900 dark:text-white uppercase italic tracking-tight">{{ $workout->name }}</h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[9px] uppercase font-bold text-zinc-400 tracking-widest">{{ $workout->difficulty }}</span>
                                    <span class="size-1 rounded-full bg-zinc-300 mt-0.5"></span>
                                    <span class="text-[9px] uppercase font-bold text-zinc-400 tracking-widest">{{ $workout->schemaExercises->count() }} oefeningen</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:button wire:click="unschedule({{ $workout->id }})" variant="subtle" size="xs" class="text-red-500" icon="x-mark" />
                                <flux:button wire:click="activateSchema({{ $workout->id }})" variant="primary" size="sm" class="!bg-cyan-500 hover:!bg-cyan-400 dark:!bg-cyan-500 dark:hover:!bg-cyan-400 !border-cyan-500 dark:!border-cyan-500 !text-zinc-950 dark:!text-zinc-950 text-[11px] py-1.5 shadow-md shadow-cyan-500/20" icon="play">Start</flux:button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 border-2 border-dashed border-zinc-100 dark:border-zinc-800 rounded-[32px] text-center bg-zinc-50/50 dark:bg-zinc-900/30 flex flex-col items-center justify-center">
                        <flux:icon name="calendar-days" class="size-10 text-zinc-200 dark:text-zinc-800" />
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:block lg:col-span-1 border-l border-zinc-100 dark:border-zinc-800 lg:pl-8 space-y-6">
            <div>
                <h2 class="text-xl font-black text-zinc-900 dark:text-white italic uppercase tracking-tight">Mijn Bibliotheek</h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Grijp en plan</p>
            </div>

            <div class="space-y-3" x-ref="sidebarList">
                @forelse($sidebarSchemas as $schema)
                    <div data-id="{{ $schema->id }}" 
                         class="cursor-grab active:cursor-grabbing p-4 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-2xl hover:border-cyan-400 transition-all shadow-sm group">
                        <div class="flex items-start justify-between">
                            <h5 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 group-hover:text-cyan-400 transition-colors line-clamp-1">{{ $schema->name }}</h5>
                            <flux:icon name="bars-2" class="size-3 text-zinc-300 group-hover:text-cyan-400" />
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                             <span class="text-[8px] px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-bold uppercase">{{ $schema->difficulty }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-500 text-center py-8 italic">Geen workouts gevonden.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Mobile Library Drawer -->
    <div x-show="showLibrary" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed inset-0 z-[100] translate-y-0 lg:hidden overflow-hidden" 
         style="display: none;">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showLibrary = false"></div>
        <div class="absolute inset-x-0 bottom-0 max-h-[85vh] bg-white dark:bg-zinc-950 rounded-t-[40px] shadow-2xl flex flex-col border-t border-zinc-200 dark:border-zinc-800">
            <div class="h-1.5 w-12 bg-zinc-200 dark:bg-zinc-800 rounded-full mx-auto my-4 shrink-0"></div>
            
            <div class="p-6 overflow-y-auto no-scrollbar pb-24">
                <div class="mb-6">
                    <h2 class="text-2xl font-black text-zinc-900 dark:text-white uppercase italic">Workouts</h2>
                    <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Tik om te plannen op {{ \Carbon\Carbon::parse($selectedDate)->format('d/m') }}</p>
                </div>

                <div class="space-y-3" x-ref="mobileSidebarList">
                    @foreach($sidebarSchemas as $schema)
                        <div data-id="{{ $schema->id }}" 
                             wire:click="handleDrop({{ $schema->id }}, '{{ $selectedDate }}')"
                             @click="showLibrary = false"
                             class="p-5 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl active:border-cyan-400 transition-all flex items-center justify-between group">
                            <div>
                                <h5 class="font-bold text-zinc-900 dark:text-zinc-100">{{ $schema->name }}</h5>
                                <span class="text-[9px] text-zinc-400 font-bold uppercase">{{ $schema->difficulty }} • {{ $schema->schemaExercises->count() }} oefeningen</span>
                            </div>
                            <div class="text-indigo-500">
                                <flux:icon name="plus-circle" class="size-5" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <button @click="showLibrary = false" class="absolute top-6 right-6 text-zinc-400 hover:text-zinc-600">
                <flux:icon name="x-mark" class="size-6" />
            </button>
        </div>
    </div>

    <!-- Mobile Floating Action Button (FAB) -->
    <button @click="showLibrary = true" 
            class="lg:hidden fixed bottom-8 right-6 size-16 rounded-full bg-vibrant text-white shadow-2xl shadow-cyan-500/40 z-[90] flex items-center justify-center active:scale-90 transition-transform hover:rotate-12">
        <flux:icon name="plus" class="size-8" />
    </button>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
