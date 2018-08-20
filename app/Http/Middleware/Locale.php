<?php

namespace Piemeram\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = $request->session();

        app()->setLocale(
            $session->has('locale')
                ? $session->get('locale')
                : config('app.locale')
        );

        return $next($request);
    }
}
