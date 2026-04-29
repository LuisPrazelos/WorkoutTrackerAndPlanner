<div>
    <x-auth-header :title="__('Weergave')" :description="__('Pas het uiterlijk van je werkruimte aan.')" />

    <div class="mt-6">
        <x-section>
            <x-section.title>
                {{ __('Thema') }}
            </x-section.title>
            <x-section.description>
                {{ __('Kies hoe je werkruimte eruit moet zien. Je kunt kiezen tussen licht en donker, of laten synchroniseren met je systeem.') }}
            </x-section.description>
            <x-section.content>
                <flux:theme-toggle />
            </x-section.content>
        </x-section>
    </div>
</div>
