<?php

namespace Piemeram\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Closure;

class OnlyMaster
{
    public function handle($request, Closure $next)
    {
        switch (Route::currentRouteName()) {
            case 'logs':
                abort_if(!auth()->check() || !in_array(auth()->user()->id, [2,3,5]), 403);
                break;
        }

        return $next($request);
    }
}
