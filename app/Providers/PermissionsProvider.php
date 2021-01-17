<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PermissionsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive("accessP", function($permission) {
            return "<?php if(auth()->check() && (auth()->user()->id === 1 || auth()->user()->hasPermission($permission))): ?>";
        });
        Blade::directive("endaccessP", function() {
            return "<?php endif; ?>";
        });
    }
}
