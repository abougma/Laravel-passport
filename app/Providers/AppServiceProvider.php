<?php

namespace App\Providers;

use App\Models\Seance;
use App\Observers\SeanceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Seance::observe(SeanceObserver::class);
    }
}
