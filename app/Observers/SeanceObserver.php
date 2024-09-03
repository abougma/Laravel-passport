<?php

namespace App\Observers;

use App\Models\Seance;

class SeanceObserver
{
    /**
     * Handle the Seance "created" event.
     *
     * @param  \App\Models\Seance  $seance
     * @return void
     */
    public function created(Seance $seance)
    {
        //
    }

    /**
     * Handle the Seance "updated" event.
     *
     * @param  \App\Models\Seance  $seance
     * @return void
     */
    public function updated(Seance $seance)
    {
        //
    }

    /**
     * Handle the Seance "deleted" event.
     *
     * @param  \App\Models\Seance  $seance
     * @return void
     */
    public function deleted(Seance $seance)
    {
        //
    }

    /**
     * Handle the Seance "restored" event.
     *
     * @param  \App\Models\Seance  $seance
     * @return void
     */
    public function restored(Seance $seance)
    {
        //
    }

    /**
     * Handle the Seance "force deleted" event.
     *
     * @param  \App\Models\Seance  $seance
     * @return void
     */
    public function forceDeleted(Seance $seance)
    {
        //
    }

    public function creating(Seance $seance)
    {
        $seance->code = $this->generateCode();
    }

    public function generateCode()
    {
        return mt_rand(10000, 999999);
    }
}
