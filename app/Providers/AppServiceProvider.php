<?php

namespace App\Providers;

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
        // Register view composers
        view()->composer('layouts.admin', \App\View\Composers\AdminLayoutComposer::class);
        view()->composer('layouts.owner', \App\View\Composers\AdminLayoutComposer::class);
    }
}
