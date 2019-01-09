<?php

namespace Piemeram\Http\Controllers\Project;

use Piemeram\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Piemeram\Services\Excel;
use Piemeram\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $this->params($request->all());

        $query = $this->movies($params);
        $perPages = $this->perPages();
        if ($params['perPage'] and
            in_array($params['perPage'], $perPages)
        ) {
            $movies = $query->paginate($params['perPage']);
        } else {
            $movies = $query->get();
        }

        $genres = $this->genres();

        return view(
            'project.movie.index',
            compact(
                'movies',
                'params',
                'perPages',
                'genres'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $genres = $this->genres();

        return view('project.movie.create', compact('movie', 'genres'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Movie $movie
     * @return \Illuminate\View\View
     */
    public function edit(Movie $movie)
    {
        $genres = $this->genres();

        return view('project.movie.edit', compact('movie', 'genres'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->rules([
            'name' => 'unique:movies,name',
        ]));

        $movie = Movie::create($request->all());

        return redirect()
            ->action('Project\MovieController@edit', $movie)
            ->with('notification', [
                'success',
                'Movie successfully added',
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Movie $movie
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Movie $movie)
    {
        $request->validate($this->rules([
            'name' => "unique:movies,name,{$movie->id}",
        ]));

        $movie->update($request->all());

        return redirect()
            ->action('Project\MovieController@edit', $movie)
            ->with('notification', [
                'success',
                'Movie successfully updated',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Movie $movie
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()
            ->action('Project\MovieController@index')
            ->with('notification', [
                'success',
                'Movie successfully deleted',
            ]);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMultiple(Request $request)
    {
        $params = $this->params($request->all());

        $ids = [];
        if ($params['checked'] and
            strcasecmp('All', trim($params['checked']))
        ) {
            $ids = $this->getIds($params['checked']);
        }
        if ($params['unchecked']) {
            $ids = $this->movies($params)
                ->get()
                ->except($this->getIds($params['unchecked']))
                ->pluck('id')
                ->toArray();
        }

        $idCount = count($ids);

        if ($idCount > 5) {
            return redirect()
            ->back()
            ->with('notification', [
                'error',
                'You can not delete more than 5 movies in one go',
            ]);
        }

        Movie::destroy($ids);

        return redirect()
            ->action('Project\MovieController@index')
            ->with('notification', [
                'success',
                "{$idCount} Movies successfully deleted",
            ]);
    }

    /**
     * Export as .xlsx
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Piemeram\Services\Excel
     */
    public function excel(Request $request)
    {
        $params = $this->params($request->all());

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
                foreach ($this->getIds($params['checked']) as $id) {
                    $ids[] = $id;
                }
            }
            if ($params['unchecked']) {
                foreach ($data->pluck(6) as $id) {
                    if (in_array($id, $this->getIds($params['unchecked']))) {
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

        return (new Excel('Movies.xlsx', $headings, $data))->download('Movies.xlsx');
    }

    /**
     * List of rules
     *
     * @param  Array $addRules
     * @return Array
     */
    protected function rules($addRules): array
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
            ],
            'genres' => [
                'required',
            ],
            'year' => [
                'numeric',
                'min:1850',
                'max:' . now()->year,
            ],
            'rating' => [
                'numeric',
                'min:0',
            ],
            'votes' => [
                'numeric',
                'min:0',
            ],
            'imdb' => [
                'required',
                'max:255',
            ],
        ];

        foreach ($addRules as $key => $rule) {
            $rules[$key][] = $rule;
        }

        return $rules;
    }

    /**
     * List of genres
     *
     * @return Array
     */
    protected function genres(): array
    {
        return [
            'Action',
            'Adult',
            'Adventure',
            'Animation',
            'Biography',
            'Comedy',
            'Crime',
            'Documentary',
            'Drama',
            'Family',
            'Fantasy',
            'History',
            'Horror',
            'Mystery',
            'Music',
            'Musical',
            'Romance',
            'Sci-Fi',
            'Short',
            'Sport',
            'Thriller',
            'War',
            'Western',
        ];
    }

    /**
     * List of items per page
     *
     * @return Array
     */
    protected function perPages(): array
    {
        return [10, 25, 50, 100, 250, 500, 1000];
    }

    /**
     * Query parameters
     *
     * @param  Array $params
     * @return Array
     */
    protected function params($params): array
    {
        return $params + [
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

    /**
     * Query builder
     *
     * @param  Array $params
     * @return Illuminate\Database\Query\Builder
     */
    protected function movies($params = [])
    {
        $params = $this->params($params);

        $query = Movie::select([
            'movies.*'
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw('name ILIKE ?', "%$search%");
        }

        if ($params['genre']) {
            $query->whereRaw('genres ILIKE ?', "%{$params['genre']}%");
        }

        if ($params['year']) {
            $query->where('year', $params['year']);
        }

        if ($params['rating']) {
            $query->whereRaw('ROUND(rating) = ?', $params['rating']);
        }

        if ($params['onlySelected']) {
            if ($params['checked'] and
                strcasecmp('All', trim($params['checked']))
            ) {
                $query->whereIn('id', $this->getIds($params['checked']));
            }
            if ($params['unchecked']) {
                $query->whereNotIn('id', $this->getIds($params['unchecked']));
            }
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }

    /**
     * Returns an array of id strings
     *
     * @param  String $string
     * @return Array
     */
    protected function getIds($string) : array
    {
        return collect(explode(',', $string))
            ->filter(function ($part) {
                return (int) $part > 0;
            })
            ->toArray();
    }
}
