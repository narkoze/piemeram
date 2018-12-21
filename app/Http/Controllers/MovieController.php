<?php

namespace Piemeram\Http\Controllers;

use Illuminate\Http\Request;
use Piemeram\Movie;

class MovieController extends Controller
{
    protected function params(): array
    {
        return [
            'search' => null,
            'genre' => null,
            'year' => null,
            'rating' => null,
        ];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all() + $this->params();

        $query = Movie::select([
            'movies.*'
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("name ILIKE ?", "%$search%");
        }

        if ($params['genre']) {
            $query->whereRaw("genres ILIKE ?", "%{$params['genre']}%");
        }

        if ($params['year']) {
            $query->where('year', $params['year']);
        }

        if ($params['rating']) {
            $query->whereRaw("ROUND(rating) = ?", $params['rating']);
        }

        $movies = $query->paginate(100);

        return view('movie.index', compact('movies', 'params'));
    }
}
