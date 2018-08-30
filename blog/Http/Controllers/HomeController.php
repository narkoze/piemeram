<?php

namespace Blog\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $auth = null;

        if (auth()->check()) {
            $auth = auth()->user()->first([
                'name',
                'email',
            ]);
        }

        return view('blog.layout', compact('auth'));
    }
}
