 <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Wachtwoord vergeten')" :description="__('Voer je e-mailadres in om een herstelkoppeling te ontvangen')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('E-mailadres')"
            type="email"
            required
            autofocus
            placeholder="e-mail@voorbeeld.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Verstuur herstelkoppeling') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Of, ga terug naar') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('aanmelden') }}</flux:link>
    </div>
</div>
