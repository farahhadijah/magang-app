<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('kelola-master-data', function ($user) {
            return in_array($user->role, ['admin', 'staff_tu']);
        });

        Gate::define('verifikasi-pengajuan', function ($user) {
            return in_array($user->role, ['dosen', 'kaprodi']);
        });
    }
}