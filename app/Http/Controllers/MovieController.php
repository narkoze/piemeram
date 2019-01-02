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
            ->transform(function ($movie) use ($params) {
                $data = [
                    $movie->name,
                    $movie->genres,
                    $movie->year,
                    $movie->rating,
                    round($movie->rating),
                    $movie->votes,
                ];

                if ($params['onlySelected']) {
                    $data[] = $movie->id;
                }

                return $data;
            });

        if ($params['onlySelected']) {
            $ids = [];
            if ($params['checked'] and
                strcasecmp('All', trim($params['checked']))
            ) {
                foreach (collect(explode(',', $params['checked']))
                    ->filter(function ($id) {
                        return (int) $id > 0;
                    })
                as $id) {
                    $ids[] = $id;
                }
            }
            if ($params['unchecked']) {
                foreach ($data->pluck(6) as $id) {
                    if (in_array(
                        $id,
                        collect(explode(',', $params['unchecked']))
                            ->filter(function ($id) {
                                return (int) $id > 0;
                            })->toArray()
                    )) {
                        continue;
                    }

                    $ids[] = $id;
                }
            }

            $headings['Id'] = [
                'format' => 'number',
                'filter' => [
                    'equal' => $ids,
                ],
            ];
        }

        return (new Excel("Movies.xlsx", $headings, $data))->download("Movies.xlsx");
    }

    protected function params(): array
    {
        return [
            'sortDirection' => 'desc',
            'onlySelected' => false,
            'sortBy' => 'votes',
            'unchecked' => '',
            'perPage' => '10',
            'rating' => null,
            'checked' => '',
            'search' => '',
            'year' => null,
            'genre' => '',
        ];
    }

    protected function perPages(): array
    {
        return [10, 25, 50, 100, 250, 500, 1000];
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

        if ($params['onlySelected']) {
            if ($params['checked'] and
                strcasecmp('All', trim($params['checked']))
            ) {
                $query->whereIn(
                    'id',
                    collect(explode(',', $params['checked']))
                        ->filter(function ($id) {
                            return (int) $id > 0;
                        })
                );
            }
            if ($params['unchecked']) {
                $query->whereNotIn(
                    'id',
                    collect(explode(',', $params['unchecked']))
                        ->filter(function ($id) {
                            return (int) $id > 0;
                        })
                );
            }
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
