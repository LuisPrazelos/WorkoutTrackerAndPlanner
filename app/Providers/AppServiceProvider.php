<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected function runningOnVercel(): bool
    {
        return (bool) (env('VERCEL') ?: env('VERCEL_URL') ?: getenv('VERCEL') ?: getenv('VERCEL_URL'));
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->runningOnVercel()) {
            $this->app->useStoragePath('/tmp/storage');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->runningOnVercel()) {
            foreach ([
                '/tmp/storage/app',
                '/tmp/storage/framework/cache/data',
                '/tmp/storage/framework/sessions',
                '/tmp/storage/framework/testing',
                '/tmp/storage/framework/views',
                '/tmp/storage/logs',
            ] as $path) {
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
            }
        }
    }
}
