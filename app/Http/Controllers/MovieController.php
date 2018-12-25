<?php

namespace Piemeram\Http\Controllers;

use Illuminate\Http\Request;
use Piemeram\Services\Excel;
use Piemeram\Movie;

class MovieController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all() + $this->params();

        $query = $this->movies($params);
        $perPages = $this->perPages();
        if ($params['perPage'] and
            in_array($params['perPage'], $perPages)
        ) {
            $movies = $query->paginate($params['perPage']);
        } else {
            $movies = $query->get();
        }

        return view('movie.index', compact('movies', 'params', 'perPages'));
    }

    public function excel(Request $request)
    {
        $params = $request->all() + $this->params();

        $headings = [
            'Name' => [
                'format' => 'text',
                'filter' => [
                    'contains' => [
                        $params['search'],
                    ],
                ],
            ],
            'Genres' => [
                'filter' => [
                    'contains' => [
                        $params['genre'],
                    ],
                ],
            ],
            'Year' => [
                'format' => 'number',
                'filter' => [
                    'equal' => $params['year'],
                ],
            ],
            'Rating' => null,
            'Stars' => [
                'format' => 'number',
                'filter' => [
                    'equal' => $params['rating'],
                ],
            ],
            'Votes' => [
                'format' => 'number',
            ],
        ];

        $data = $this->movies()
            ->get()
            ->transform(function ($movie) {
                return [
                    $movie->name,
                    $movie->genres,
                    $movie->year,
                    $movie->rating,
                    round($movie->rating),
                    $movie->votes,
                ];
            });

        return (new Excel("Movies.xlsx", $headings, $data))->download("Movies.xlsx");
    }

    protected function params(): array
    {
        return [
            'genre' => null,
            'year' => null,
            'rating' => null,
            'search' => '',
            'sortBy' => 'votes',
            'sortDirection' => 'desc',
            'perPage' => '10'
        ];
    }

    protected function perPages(): array
    {
        return [10, 25, 50, 100, 250, 500, 100];
    }

    protected function movies($params = [])
    {
        $params = $params + $this->params();

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

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
