<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Throwable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            $validated['password'] = Hash::make($validated['password']);

            event(new Registered(($user = User::create($validated))));

            Auth::login($user);

            $this->redirect(route('dashboard', absolute: false), navigate: true);
        } catch (Throwable $e) {
            error_log('LIVEWIRE_REGISTRATION_FAILED: ' . $e::class . ': ' . $e->getMessage());
            error_log('LIVEWIRE_REGISTRATION_FAILED_FILE: ' . $e->getFile() . ':' . $e->getLine());

            throw $e;
        }
    }
}
