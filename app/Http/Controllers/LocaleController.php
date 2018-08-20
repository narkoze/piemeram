<?php

namespace Piemeram\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLocale(Request $request, $locale)
    {
        $request->session()->put('locale', $locale);

        return response()
            ->view('preference.response.refresh')
            ->header('Content-Type', 'application/javascript');
    }
}
