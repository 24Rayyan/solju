<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\ServiceProvider as DomPDFServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Daftarkan service provider untuk DomPDF
        $this->app->register(DomPDFServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    
    
}
