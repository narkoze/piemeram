<?php

namespace Piemeram\Http\Controllers;

class HomeController extends Controller
{
    protected $tab = 'home';

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('project');
        // return view('welcome');
    }
}
