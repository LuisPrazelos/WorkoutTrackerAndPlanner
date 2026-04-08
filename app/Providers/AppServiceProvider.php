<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (isset($_SERVER['VERCEL_URL'])) {
            $this->app->useStoragePath('/tmp/storage');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_SERVER['VERCEL_URL'])) {
            $storagePath = '/tmp/storage/framework';
            foreach (['views', 'sessions', 'cache'] as $path) {
                if (!is_dir($storagePath . '/' . $path)) {
                    mkdir($storagePath . '/' . $path, 0755, true);
                }
            }
        } 
    }
}
