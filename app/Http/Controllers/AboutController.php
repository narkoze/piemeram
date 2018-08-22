<?php

namespace Piemeram\Http\Controllers;

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
        $driving = Carbon::createFromDate(2008, 4, 11)->diff(Carbon::now())->y;

        return view(
            'about.index',
            compact(
                'age',
                'driving'
            )
        );
    }
}
