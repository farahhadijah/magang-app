<?php

namespace App\Observers;

use App\Models\Staff;
use App\Services\UserAutoCreateService;

class StaffObserver
{
    /**
     * Handle the Staff "created" event.
     */
    public function created(Staff $staff): void
    {
           $role = match ($staff->jabatan) {
            'Staff TU' => 'staff_tu',
            'Kaprodi'  => 'kaprodi',
            default    => 'staff_tu',
        };

        UserAutoCreateService::fromStaff($staff, $role);
    }

    /**
     * Handle the Staff "updated" event.
     */
    public function updated(Staff $staff): void
    {
        //
    }

    /**
     * Handle the Staff "deleted" event.
     */
    public function deleted(Staff $staff): void
    {
        //
    }

    /**
     * Handle the Staff "restored" event.
     */
    public function restored(Staff $staff): void
    {
        //
    }

    /**
     * Handle the Staff "force deleted" event.
     */
    public function forceDeleted(Staff $staff): void
    {
        //
    }
}