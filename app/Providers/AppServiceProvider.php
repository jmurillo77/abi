<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::define('assign-menu-permissions', function ($user) {
            $admins = array_filter(array_map('trim', explode(',', env('ADMINS', ''))));
            return $user && $user->email && in_array($user->email, $admins);
        });
    }
}
