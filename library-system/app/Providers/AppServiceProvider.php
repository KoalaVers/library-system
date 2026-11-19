<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Hanya Admin yang dapat mengelola pengguna.
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });

        // Admin dan Petugas dapat mengelola anggota.
        Gate::define('manage-members', function ($user) {
            return in_array($user->role, ['admin', 'petugas'], true);
        });
    }
}
