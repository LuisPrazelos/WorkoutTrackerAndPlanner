<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Wachtwoord opnieuw instellen')" :description="__('Voer hieronder je nieuwe wachtwoord in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('E-mail')"
            type="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Wachtwoord')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Wachtwoord')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Wachtwoord bevestigen')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Wachtwoord bevestigen')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Wachtwoord opnieuw instellen') }}
            </flux:button>
        </div>
    </form>
</div>
