<div class="flex flex-col gap-8">
    <div class="text-center space-y-2">
        <h2 class="text-2xl font-bold tracking-tight text-white font-heading">{{ __('Welcome Back') }}</h2>
        <p class="text-sm text-zinc-400">{{ __('Enter your credentials to access your workout plan.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="name@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute top-0 text-xs font-medium end-0 text-indigo-400 hover:text-indigo-300 transition-colors" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Keep me logged in')" />

        <div class="pt-2">
            <flux:button type="submit" class="w-full bg-vibrant hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] text-white font-bold py-3 transition-all active:scale-95" data-test="login-button">
                {{ __('Secure Log In') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-sm text-center text-zinc-500">
            <span>{{ __('New to the program?') }}</span>
            <flux:link :href="route('register')" class="text-indigo-400 font-semibold" wire:navigate>{{ __('Join now') }}</flux:link>
        </div>
    @endif
</div>
