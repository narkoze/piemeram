<?php

namespace Piemeram\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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
//       $this->app->bind('path.public', function() { // workaround
//           return base_path().'/../public_html'; // workaround
//       }); // workaround
    }
}
