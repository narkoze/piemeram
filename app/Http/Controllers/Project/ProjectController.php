<?php

namespace Piemeram\Http\Controllers\Project;

use Piemeram\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $tab = 'project';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('project.index');
    }
}
