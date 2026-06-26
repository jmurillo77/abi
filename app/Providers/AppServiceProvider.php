<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

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

        Livewire::component('profile.update-profile-information-form', \App\Livewire\Profile\UpdateProfileInformationForm::class);

        Gate::define('assign-menu-permissions', function ($user) {
            $admins = array_filter(array_map('trim', explode(',', env('ADMINS', ''))));

            if (! $user instanceof \App\Models\User) {
                return false;
            }

            if ($user->email && in_array($user->email, $admins, true)) {
                return true;
            }

            $user->loadMissing('role');

            if (! $user->role) {
                return false;
            }

            return (int) $user->role->IdRol === 1
                || stripos((string) $user->role->Nombre, 'admin') !== false;
        });

        Blade::if('submenuCan', function (string $action, ?string $routeName = null) {
            $user = Auth::user();

            if (! $user instanceof \App\Models\User) {
                return false;
            }

            return $user->canSubmenuAction($action, $routeName);
        });
    }
}
