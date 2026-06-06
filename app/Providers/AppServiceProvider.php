<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Staff;
use App\Models\Mahasiswa;
use App\Models\Pimpinan;

use App\Observers\DosenObserver;
use App\Observers\StaffObserver;
use App\Observers\MahasiswaObserver;
use App\Observers\PimpinanObserver;

use App\View\Composers\MahasiswaNavbarComposer;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
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
        // Observer
        Mahasiswa::observe(MahasiswaObserver::class);
        Dosen::observe(DosenObserver::class);
        Staff::observe(StaffObserver::class);
        Pimpinan::observe(PimpinanObserver::class);

        View::composer('layouts.navigation', function ($view) {
            $view->with(
                'fakultas',
                DB::table('fakultas')->get()
            );
        });

        // Navbar Mahasiswa
        View::composer(
            'layouts.navbar.mahasiswa',
            MahasiswaNavbarComposer::class
        );
    }
}