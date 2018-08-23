<?php

namespace Piemeram\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Response;

class AboutController extends Controller
{
    protected $tab = 'about';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $age = Carbon::createFromDate(1989, 4, 11)->age;
        $driving = Carbon::createFromDate(2008, 4, 11)->diff(Carbon::now())->y;

        $compact = compact(
            'age',
            'driving'
        );

        if ($request->pdf == 'show') {
            return $this->pdf($compact);
        }

        return view('about.index', $compact);
    }

    protected function pdf($compact)
    {
        $dompdf = new Dompdf();
        $title = "Edgars Vanags CV Programmētājs - datorsistēmu tehniķis.pdf";
        $dompdf->loadHtml(
            view(
                'about.pdf',
                compact('title') + $compact
            )
        );

        $dompdf->render();

        return Response::make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename*=UTF-8''" . rawurlencode($title)
        ]);
    }
}
