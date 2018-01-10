<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AboutController extends Controller
{
    protected $tab = 'about';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $age = Carbon::createFromDate(1989, 4, 11)->age;

        return view('about.index', compact('age'));
    }
}
