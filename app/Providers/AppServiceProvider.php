<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    protected function runningOnVercel(): bool
    {
        return (bool) (env('VERCEL') ?: env('VERCEL_URL') ?: getenv('VERCEL') ?: getenv('VERCEL_URL'));
    }

    protected function ensureDirectory(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        if (@mkdir($path, 0755, true) || is_dir($path)) {
            return;
        }

        throw new \RuntimeException("Unable to create directory [{$path}].");
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
            URL::forceScheme('https');

            foreach ([
                '/tmp/storage/app',
                '/tmp/storage/framework/cache/data',
                '/tmp/storage/framework/sessions',
                '/tmp/storage/framework/testing',
                '/tmp/storage/framework/views',
                '/tmp/storage/logs',
            ] as $path) {
                $this->ensureDirectory($path);
            }
        }
    }
}
