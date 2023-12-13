<?php

namespace App\Providers;

use App\Helper\Helper;
use App\Models\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind('helper', function () {
            return new Helper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $logo_home = Settings::where("name", "home page logo")->first();
        $logo = Settings::where("name", "logo")->first();
        $target = Settings::where("name", "answer target")->first();
        view()->share('logo_home', $logo_home);
        view()->share('logo', $logo);
        view()->share('target', $target);
        Builder::defaultStringLength(191);
        Paginator::useBootstrap();
    }
}
