<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ringkasan email Staff TU
Schedule::command('sibolang:ringkasan-staff-tu')
    ->weekdays()
    ->dailyAt('08:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

// Ringkasan email Kaprodi
Schedule::command('sibolang:ringkasan-kaprodi')
    ->weekdays()
    ->dailyAt('08:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();