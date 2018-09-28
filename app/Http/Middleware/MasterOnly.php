<?php

namespace Piemeram\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Closure;

class MasterOnly
{
    public function handle($request, Closure $next)
    {
        switch (Route::currentRouteName()) {
            case 'logs':
                abort_if(!auth()->check() || !in_array(auth()->user()->id, [1,3,5]), 403);
                break;
        }

        return $next($request);
    }
}
