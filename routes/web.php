<?php

use App\Livewire\CreateWorkoutSchema;
use App\Livewire\Dashboard;
use App\Livewire\ShowWorkoutSchema;
use App\Livewire\Stats;
use App\Livewire\WorkoutLog;
use App\Livewire\WorkoutSchemas;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\JoinWorkoutSchema;
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
    // Iedereen mag een nieuw schema aanmaken
    Route::get('workout-schemas/create', CreateWorkoutSchema::class)->name('workout-schemas.create');

    // Schema bibliotheek (alle gebruikers)
    Route::get('schemas', WorkoutSchemas::class)->name('schemas.index');


    // Workout schema detail
    Route::get('workout-schemas/{workoutSchema}', ShowWorkoutSchema::class)->name('workout-schemas.show');

    // Join/Share route
    Route::get('join/{token}', JoinWorkoutSchema::class)->name('workout-schemas.join');

    // Dagelijks workout logboek
    Route::get('workout-log', WorkoutLog::class)->name('workout-log');

    // Statistieken
    Route::get('stats', Stats::class)->name('stats');

    // Agenda
    Route::get('agenda', App\Livewire\Agenda::class)->name('agenda');

    // PDF export
    Route::get('workout-schemas/{workoutSchema}/export-pdf', [App\Http\Controllers\WorkoutSchemaController::class, 'exportPdf'])
        ->name('workout-schemas.export-pdf');

    // Settings
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

    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('admin/users', \App\Livewire\Admin\UserManagement::class)->name('admin.users');
    });
});

require __DIR__.'/auth.php';
