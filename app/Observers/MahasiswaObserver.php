<?php

namespace App\Observers;

use App\Models\Mahasiswa;
use App\Services\UserAutoCreateService;
class MahasiswaObserver
{
    /**
     * Handle the Mahasiswa "created" event.
     */
    public function created(Mahasiswa $mahasiswa): void
    {
        UserAutoCreateService::fromMahasiswa($mahasiswa);
    }

    /**
     * Handle the Mahasiswa "updated" event.
     */
    public function updated(Mahasiswa $mahasiswa): void
    {
        //
    }

    /**
     * Handle the Mahasiswa "deleted" event.
     */
    public function deleted(Mahasiswa $mahasiswa): void
    {
        //
    }

    /**
     * Handle the Mahasiswa "restored" event.
     */
    public function restored(Mahasiswa $mahasiswa): void
    {
        //
    }

    /**
     * Handle the Mahasiswa "force deleted" event.
     */
    public function forceDeleted(Mahasiswa $mahasiswa): void
    {
        //
    }
}