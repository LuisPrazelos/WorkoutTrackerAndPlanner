<div>
    <x-auth-header :title="__('Appearance')" :description="__('Customize the look and feel of your workspace.')" />

    <div class="mt-6">
        <x-section>
            <x-section.title>
                {{ __('Theme') }}
            </x-section.title>
            <x-section.description>
                {{ __('Select how you want your workspace to look. You can choose between light and dark, or have it sync with your system.') }}
            </x-section.description>
            <x-section.content>
                <flux:theme-toggle />
            </x-section.content>
        </x-section>
    </div>
</div>
