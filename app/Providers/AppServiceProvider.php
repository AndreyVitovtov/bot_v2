<?php

namespace App\Providers;

use App\Services\Contracts\AdminService;
use App\Services\Contracts\BotService;
use App\Services\Contracts\ContactService;
use App\Services\Contracts\InteractionService;
use App\Services\Contracts\LanguageService;
use App\Services\Contracts\ModeratorsService;
use App\Services\Contracts\PermissionService;
use App\Services\Contracts\ReferralSystemService;
use App\Services\Contracts\RoleService;
use App\Services\Contracts\UserService;
use App\Services\Implement\AdminServiceImpl;
use App\Services\Implement\BotServiceImpl;
use App\Services\Implement\ContactServiceImpl;
use App\Services\Implement\InteractionServiceImpl;
use App\Services\Implement\LanguageServiceImpl;
use App\Services\Implement\ModeratorsServiceImpl;
use App\Services\Implement\PermissionServiceImpl;
use App\Services\Implement\ReferralSystemImpl;
use App\Services\Implement\RoleServiceImpl;
use App\Services\Implement\UserServiceImpl;
use Illuminate\Pagination\Paginator;
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
        $this->app->singleton(AdminService::class, function() {
            return new AdminServiceImpl();
        });

        $this->app->singleton(ContactService::class, function() {
            return new ContactServiceImpl();
        });

        $this->app->singleton(InteractionService::class, function() {
            return new InteractionServiceImpl();
        });

        $this->app->singleton(LanguageService::class, function() {
            return new LanguageServiceImpl();
        });

        $this->app->singleton(PermissionService::class, function() {
            return new PermissionServiceImpl();
        });

        $this->app->singleton(ReferralSystemService::class, function() {
            return new ReferralSystemImpl();
        });

        $this->app->singleton(RoleService::class, function() {
            return new RoleServiceImpl();
        });

        $this->app->singleton(UserService::class, function() {
            return new UserServiceImpl();
        });

        $this->app->singleton(ModeratorsService::class, function() {
            return new ModeratorsServiceImpl();
        });

        $this->app->singleton(BotService::class, function() {
            return new BotServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
