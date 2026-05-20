<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Staff;
use App\Models\Mahasiswa;
use App\Observers\DosenObserver;
use App\Observers\StaffObserver;
use App\Observers\MahasiswaObserver;
use App\Models\Pimpinan;
use App\Observers\PimpinanObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

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
        Mahasiswa::observe(MahasiswaObserver::class);
        Dosen::observe(DosenObserver::class);
        Staff::observe(StaffObserver::class);
        Pimpinan::observe(PimpinanObserver::class);
        View::composer('layouts.navigation', function ($view) {
        $fakultas = DB::table('fakultas')->get();
        $view->with('fakultas', $fakultas);
    });
    }
}