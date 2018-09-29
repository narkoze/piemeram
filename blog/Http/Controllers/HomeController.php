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
            $user = auth()->user();

            $auth = $user->only([
                'id',
                'name',
            ]) + [
                'verified' => $user->hasVerifiedEmail(),
            ];
        }

        return view('blog.layout', compact('auth'));
    }
}
