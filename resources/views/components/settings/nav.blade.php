<div class="flex flex-col gap-y-2">
    <x:settings.nav.item :href="route('settings.profile')" :is-active="request()->routeIs('settings.profile')">
        {{ __('Profiel') }}
    </x:settings.nav.item>
    <x:settings.nav.item :href="route('settings.password')" :is-active="request()->routeIs('settings.password')">
        {{ __('Wachtwoord') }}
    </x:settings.nav.item>
    <x:settings.nav.item :href="route('settings.appearance')" :is-active="request()->routeIs('settings.appearance')">
        {{ __('Weergave') }}
    </x:settings.nav.item>
    @if(Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <x:settings.nav.item :href="route('two-factor.show')" :is-active="request()->routeIs('two-factor.show')">
            {{ __('Tweefactorauthenticatie') }}
        </x:settings.nav.item>
    @endif
</div>
