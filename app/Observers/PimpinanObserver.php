<?php

namespace App\Observers;
use App\Services\UserAutoCreateService;
use App\Models\Pimpinan;

class PimpinanObserver
{
    /**
     * Handle the Pimpinan "created" event.
     */
      public function created(Pimpinan $pimpinan): void
    {
        UserAutoCreateService::fromPimpinan($pimpinan);
    }

    // OPTIONAL (biar sinkron kalau diupdate)
    public function updated(Pimpinan $pimpinan): void
    {
        UserAutoCreateService::fromPimpinan($pimpinan);
    }

    /**
     * Handle the Pimpinan "deleted" event.
     */
    public function deleted(Pimpinan $pimpinan): void
    {
        //
    }

    /**
     * Handle the Pimpinan "restored" event.
     */
    public function restored(Pimpinan $pimpinan): void
    {
        //
    }

    /**
     * Handle the Pimpinan "force deleted" event.
     */
    public function forceDeleted(Pimpinan $pimpinan): void
    {
        //
    }
}