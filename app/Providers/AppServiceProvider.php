<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NavigationTools;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register NavigationTools as a singleton
        $this->app->singleton(NavigationTools::class, function ($app) {
            return new NavigationTools();
        });

        // Alternatively, you can also bind it with an alias
        $this->app->alias(NavigationTools::class, 'navigation-tools');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // You can add any boot-time configuration here if needed
    }
}
