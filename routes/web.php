<?php

use App\Http\Middleware\EnsureUserIsTrainer;
use App\Livewire\AssignSchema;
use App\Livewire\CreateWorkoutSchema;
use App\Livewire\Dashboard;
use App\Livewire\ShowWorkoutSchema;
use App\Livewire\Stats;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::middleware([EnsureUserIsTrainer::class])->group(function () {
        Route::get('workout-schemas/create', CreateWorkoutSchema::class)->name('workout-schemas.create');
        Route::get('assign-schema', AssignSchema::class)->name('assign-schema');
    });

    Route::get('workout-schemas/{workoutSchema}', ShowWorkoutSchema::class)->name('workout-schemas.show');

    Route::get('stats', Stats::class)->name('stats');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
