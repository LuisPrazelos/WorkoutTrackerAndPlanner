<div class="flex flex-col gap-8">
    <div class="text-center space-y-2">
        <h2 class="text-2xl font-bold tracking-tight text-white font-heading">{{ __('Account Aanmaken') }}</h2>
        <p class="text-sm text-zinc-400">{{ __('Maak een account aan om vandaag je progressie bij te houden.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Volledige Naam')"
            type="text"
            required
            autofocus
            autocomplete="name"
            placeholder="Jan Janssen"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('E-mailadres')"
            type="email"
            required
            autocomplete="email"
            placeholder="naam@voorbeeld.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Wachtwoord Aanmaken')"
            type="password"
            required
            autocomplete="new-password"
            placeholder="••••••••"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Wachtwoord Bevestigen')"
            type="password"
            required
            autocomplete="new-password"
            placeholder="••••••••"
            viewable
        />

        <div class="pt-2">
            <flux:button type="submit" class="w-full bg-vibrant hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] text-white font-bold py-3 transition-all active:scale-95">
                {{ __('Account Aanmaken') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-500">
        <span>{{ __('Al een account?') }}</span>
        <flux:link :href="route('login')" class="text-indigo-400 font-semibold" wire:navigate>{{ __('Aanmelden') }}</flux:link>
    </div>
</div>
