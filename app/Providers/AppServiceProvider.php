<?php

namespace Piemeram\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::semantic-ui');

        Blade::if('verified', function ($bool = true) {
            $authenticated = auth()->check();
            $verified = auth()->user()->hasVerifiedEmail();

            return $bool ? $authenticated and $verified : $authenticated and !$verified;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
