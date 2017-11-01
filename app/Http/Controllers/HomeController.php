<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    protected $tab = 'home';

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }
}
