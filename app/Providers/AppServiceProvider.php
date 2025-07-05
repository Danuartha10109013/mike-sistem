<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();
        Filament::registerRenderHook('head.start', function () {
            return '<link rel="icon" type="image/png" href="' . asset('favicon.ico') . '">';
        });

        Filament::registerNavigationGroups([
            'Inventory',
            'Data',
            'Access',
        ]);
    }
}
