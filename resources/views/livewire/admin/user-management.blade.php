<div class="p-6 lg:p-8">
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white font-heading underline decoration-indigo-500 decoration-4 underline-offset-8 uppercase italic">Gebruikersbeheer</h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Beheer je atleten en trainers op één plek.</p>
        </div>

        <div class="w-full lg:w-72">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Atleten zoeken..." />
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 p-4 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 p-4 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="glass-card overflow-hidden">
        {{-- Desktop Table --}}
        <div class="hidden md:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Gebruiker</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Rol</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <flux:badge size="sm" :color="$user->role === 'admin' ? 'purple' : ($user->role === 'trainer' ? 'indigo' : 'zinc')">
                                    {{ ucfirst($user->role) }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <flux:dropdown position="bottom" align="end">
                                        <flux:button variant="ghost" size="sm" icon="adjustments-horizontal">Rol</flux:button>
                                        <flux:menu>
                                            <flux:menu.item wire:click="setRole({{ $user->id }}, 'member')">Maak Lid</flux:menu.item>
                                            <flux:menu.item wire:click="setRole({{ $user->id }}, 'trainer')">Maak Trainer</flux:menu.item>
                                            <flux:menu.item wire:click="setRole({{ $user->id }}, 'admin')">Maak Admin</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>

                                    <flux:button
                                        variant="ghost"
                                        size="sm"
                                        icon="trash"
                                        color="red"
                                        wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Weet je zeker dat je deze gebruiker wilt verwijderen?"
                                    />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Grid --}}
        <div class="md:hidden divide-y divide-zinc-200 dark:divide-zinc-700">
            @foreach($users as $user)
                <div class="p-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold">
                            {{ $user->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-zinc-900 dark:text-white truncate">{{ $user->name }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $user->email }}</div>
                        </div>
                        <flux:badge size="xs" :color="$user->role === 'admin' ? 'purple' : ($user->role === 'trainer' ? 'indigo' : 'zinc')">
                            {{ ucfirst($user->role) }}
                        </flux:badge>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <flux:dropdown position="bottom" align="start" class="flex-1">
                            <flux:button variant="subtle" size="sm" class="w-full" icon="adjustments-horizontal">Rol Wijzigen</flux:button>
                            <flux:menu>
                                <flux:menu.item wire:click="setRole({{ $user->id }}, 'member')">Maak Lid</flux:menu.item>
                                <flux:menu.item wire:click="setRole({{ $user->id }}, 'trainer')">Maak Trainer</flux:menu.item>
                                <flux:menu.item wire:click="setRole({{ $user->id }}, 'admin')">Maak Admin</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>

                        <flux:button
                            variant="subtle"
                            size="sm"
                            icon="trash"
                            color="red"
                            wire:click="deleteUser({{ $user->id }})"
                            wire:confirm="Weet je zeker?"
                        />
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
