<?php

namespace App\Observers;

use App\Models\ShopVisitLog;

class ShopVisitLogObserver
{
    /**
     * Handle the ShopVisitLog "created" event.
     */
    public function created(ShopVisitLog $shopVisitLog): void
    {
        //
    }

    /**
     * Handle the ShopVisitLog "updated" event.
     */
    public function updated(ShopVisitLog $shopVisitLog): void
    {
        //
    }

    /**
     * Handle the ShopVisitLog "deleted" event.
     */
    public function deleted(ShopVisitLog $shopVisitLog): void
    {
        //
    }

    /**
     * Handle the ShopVisitLog "restored" event.
     */
    public function restored(ShopVisitLog $shopVisitLog): void
    {
        //
    }

    /**
     * Handle the ShopVisitLog "force deleted" event.
     */
    public function forceDeleted(ShopVisitLog $shopVisitLog): void
    {
        //
    }
}
