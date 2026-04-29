<div class="mt-4 flex flex-col gap-6">
    <flux:text class="text-center">
        {{ __('Verifieer je e-mailadres door op de koppeling te klikken die we je zojuist hebben gemaild.') }}
    </flux:text>

    @if (session('status') == 'verification-link-sent')
        <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('Een nieuwe verificatiekoppeling is verzonden naar het e-mailadres dat je opgaf bij registratie.') }}
        </flux:text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Verificatie-e-mail opnieuw versturen') }}
        </flux:button>

        <flux:link class="text-sm cursor-pointer" wire:click="logout">
            {{ __('Uitloggen') }}
        </flux:link>
    </div>
</div>
