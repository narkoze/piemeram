<!doctype html>
<html lang="EN">
  <head>
    <title>Piemeram.lv</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ mix('css/movie.css') }}" />
  </head>
  <body>
    <div class="ui container">
      <h1 class="ui header">Movies</h1>

      <div class="ui grid stackable form">
        <div class="four wide column">
          <div class="field">
            <label>Genre</label>
            <select class="ui search dropdown genre">
              <option
                value=""
                selected
              >
                &hellip;
              </option>

              @php
                $genres = [
                  "Action",
                  "Adult",
                  "Adventure",
                  "Animation",
                  "Biography",
                  "Comedy",
                  "Crime",
                  "Documentary",
                  "Drama",
                  "Family",
                  "Fantasy",
                  "History",
                  "Horror",
                  "Mystery",
                  "Music",
                  "Musical",
                  "Romance",
                  "Sci-Fi",
                  "Short",
                  "Sport",
                  "Thriller",
                  "War",
                  "Western",
                ];
                if ($params['genre'] and !in_array($params['genre'], $genres)) {
                  array_unshift($genres, $params['genre']);
                }
              @endphp

              @foreach ($genres as $genre)
                <option
                  @unless (strcasecmp($genre, $params['genre']))
                    selected
                  @endunless
                  value="{{ $genre }}"
                >
                  {{ $genre }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="field">
            <label>Year</label>
            <select class="ui search dropdown year">
              <option value="">&hellip;</option>

              @foreach (range(now()->year, 1939) as $year)
                <option
                  @if ($year == $params['year'])
                    selected
                  @endif
                  value="{{ $year }}"
                >
                  {{ $year }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="field">
            <label>Rating</label>
            <div class="ui selection dropdown rate">
              @php
                $ratings = range(10, 1);
              @endphp

              <div class="text">
                @if (in_array($params['rating'], $ratings))
                  {{ $params['rating'] }}
                  <div
                    data-rating="{{ $params['rating'] }}"
                    data-max-rating="10"
                    class="ui star rating"
                  >
                  </div>
                @else
                  &hellip;
                @endif
              </div>

              <div class="menu">
                <div
                  class="item active"
                  data-value="0"
                >
                  &hellip;
                </div>

                @foreach ($ratings as $rating)
                  <div
                    data-value="{{ $rating }}"
                    class="
                      item
                      @if ($params['rating'] == $rating)
                        active selected
                      @endif
                    "
                  >
                    {{ $rating }}
                    <div
                      data-rating="{{ $rating }}"
                      data-max-rating="10"
                      class="ui star rating"
                    >
                    </div>
                  </div>
                @endforeach
              </div>

              <i class="dropdown icon"></i>
            </div>
          </div>

          <div class="field">
            <a
              href="{{ route('movie') }}?{{ http_build_query([
                'genre' => '',
                'year' => '',
                'rating' => '',
                'page' => 1,
              ] + $params) }}"
            >
              Remove filters
            </a>
          </div>
        </div>

        <div class="twelve wide column">
          <form
            action="{{ route('movie') }}"
            id="form"
          >
            <input
              value="{{ $params['genre'] }}"
              type="hidden"
              name="genre"
            >
            <input
              value="{{ $params['year'] }}"
              type="hidden"
              name="year"
            >
            <input
              value="{{ $params['rating'] }}"
              type="hidden"
              name="rating"
            >

            <div class="field">
              <label>&nbsp;</label>

              <div class="ui fluid left icon right labeled action input">
                <i class="search icon"></i>

                @php
                  $search = trim($params['search']);
                @endphp

                <input
                  value="{{ $search }}"
                  type="text"
                  id="search"
                  name="search"
                  placeholder="Press enter to search"
                >

                @if ($search)
                  <a
                    href="{{ route('movie') }}?{{ http_build_query([
                      'search' => '',
                      'page' => 1,
                    ] + $params) }}"
                    class="ui button"
                  >
                    <i class="fitted times icon"></i>
                  </a>
                @else
                  <button
                    type="submit"
                    class="ui button"
                  >
                    Search
                  </button>
                @endif
              </div>
            </div>
          </form>

          <div class="ui segment paddingless">
            <div
              data-tooltip="Download as Excel"
              data-position="right center"
            >
              <a
                href="{{ route('movie.excel') }}?{{ http_build_query($params) }}"
                class="ui large green right corner label"
              >
                <i class="file excel outline icon"></i>
              </a>
            </div>

            <table class="ui selectable celled table">
              <thead>
                <tr>
                  <th class="nowrap">
                    <a
                      href="{{ route('movie') }}?{{ http_build_query([
                        'sortBy' => 'name',
                        'sortDirection' => $params['sortBy'] == 'name' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                        'page' => 1,
                      ] + $params) }}"
                    >
                      Name
                      @if ($params['sortBy'] == 'name')
                        <i class="angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                      @endif
                    </a>
                    @if ($search)
                      <a
                        href="{{ route('movie') }}?{{ http_build_query([
                          'search' => '',
                          'page' => 1,
                        ] + $params) }}"
                        data-tooltip="Remove search"
                        data-inverted=""
                      >
                        <i class="icons">
                          <i class="search icon"></i>
                          <i class="top right corner times icon red"></i>
                        </i>
                      </a>
                    @endif
                  </th>

                  <th class="nowrap">
                    <a
                      href="{{ route('movie') }}?{{ http_build_query([
                        'sortBy' => 'genres',
                        'sortDirection' => $params['sortBy'] == 'genres' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                        'page' => 1,
                      ] + $params) }}"
                    >
                      Genres
                      @if ($params['sortBy'] == 'genres')
                        <i class="angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                      @endif
                    </a>
                    @if ($params['genre'])
                      <a
                        href="{{ route('movie') }}?{{ http_build_query([
                          'genre' => '',
                          'page' => 1,
                        ] + $params) }}"
                        data-tooltip="Remove genre filter"
                        data-inverted=""
                      >
                        <i class="icons">
                          <i class="filter icon"></i>
                          <i class="top right corner times icon red"></i>
                        </i>
                      </a>
                    @endif
                  </th>

                  <th class="nowrap">
                    <a
                      href="{{ route('movie') }}?{{ http_build_query([
                        'sortBy' => 'year',
                        'sortDirection' => $params['sortBy'] == 'year' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                        'page' => 1,
                      ] + $params) }}"
                    >
                      Year
                      @if ($params['sortBy'] == 'year')
                        <i class="angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                      @endif
                    </a>
                    @if ($params['year'])
                      <a
                        href="{{ route('movie') }}?{{ http_build_query([
                          'year' => '',
                          'page' => 1,
                        ] + $params) }}"
                        data-tooltip="Remove year filter"
                        data-inverted=""
                      >
                        <i class="icons">
                          <i class="filter icon"></i>
                          <i class="top right corner times icon red"></i>
                        </i>
                      </a>
                    @endif
                  </th>

                  <th class="nowrap">
                    <a
                      href="{{ route('movie') }}?{{ http_build_query([
                        'sortBy' => 'rating',
                        'sortDirection' => $params['sortBy'] == 'rating' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                        'page' => 1,
                      ] + $params) }}"
                    >
                      Rating
                      @if ($params['sortBy'] == 'rating')
                        <i class="angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                      @endif
                    </a>
                    @if ($params['rating'])
                      <a
                        href="{{ route('movie') }}?{{ http_build_query([
                          'rating' => '',
                          'page' => 1,
                        ] + $params) }}"
                        data-tooltip="Remove rating filter"
                        data-inverted=""
                      >
                        <i class="icons">
                          <i class="filter icon"></i>
                          <i class="top right corner times icon red"></i>
                        </i>
                      </a>
                    @endif
                  </th>

                  <th class="nowrap fixed-8em">
                    <a
                      href="{{ route('movie') }}?{{ http_build_query([
                        'sortBy' => 'votes',
                        'sortDirection' => $params['sortBy'] == 'votes' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                        'page' => 1,
                      ] + $params) }}"
                    >
                      Votes
                      @if ($params['sortBy'] == 'votes')
                        <i class="angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                      @endif
                    </a>
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($movies as $movie)
                  <tr>
                    <td>
                      <a
                        href="{{ $movie->imdb }}"
                        target="_blank"
                        data-tooltip="Open IMDB link in a new tab"
                      >
                        @if ($search)
                          {!! preg_replace("/($search)/i", "<u class='highlight'>$0</u>", $movie->name) !!}
                        @else
                          {{ $movie->name }}
                        @endif
                      </a>
                    </td>

                    <td>
                      @foreach (explode(',', $movie->genres) as $genre)
                        @php
                          $genre = trim($genre);
                        @endphp

                        @if (strcasecmp($genre, trim($params['genre'])) == 0)
                          {{ $genre }}@unless ($loop->last), @endunless
                        @else
                          <a
                            href="{{ route('movie') }}?{{ http_build_query([
                              'genre' => $genre,
                              'page' => 1,
                            ] + $params) }}"
                            data-tooltip="Filter by {{ $genre }}"
                            data-inverted=""
                            class="nowrap"
                          >
                            {!! preg_replace("/(". trim($params['genre']) .")/i", "<u class='highlight'>$0</u>", $genre) !!}</a>@unless ($loop->last), @endunless
                        @endif
                      @endforeach
                    </td>

                    <td>
                      @if ($params['year'])
                        {{ $movie->year }}
                      @else
                        <a
                          href="{{ route('movie') }}?{{ http_build_query([
                            'year' => $movie->year,
                            'page' => 1,
                          ] + $params) }}"
                          data-tooltip="Filter by {{ $movie->year }}"
                          data-inverted=""
                        >
                          {{ $movie->year }}
                        </a>
                      @endif
                    </td>

                    <td class="nowrap">
                      {{ $movie->rating }}

                      @php
                        $stars = round($movie->rating);
                      @endphp

                      <a
                        @unless ($params['rating'])
                          href="{{ route('movie') }}?{{ http_build_query([
                            'rating' => $stars,
                            'page' => 1,
                          ] + $params) }}"
                        @endunless
                        class="
                          ui star rating
                          @unless ($params['rating'])
                            pointer
                          @endif
                        "
                        data-rating="{{ $stars }}"
                        data-max-rating="10"
                        data-tooltip="Filter by {{ $stars }}"
                        data-inverted=""
                      >
                      </a>
                    </td>

                    <td>{{ number_format($movie->votes) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="ui compact floating menu">
            <div class="ui simple dropdown item">
              Per page {{ $params['perPage'] }}
              <i class="dropdown icon"></i>

              <div class="top menu">
                @php
                  $perPages[] = 'All';
                @endphp

                @foreach ($perPages as $perPage)
                  @unless ($params['perPage'] == $perPage)
                    <a
                      href="{{ route('movie') }}?{{ http_build_query([
                        'perPage' => $perPage,
                        'page' => 1,
                      ] + $params) }}"
                      class="item"
                    >
                      {{ $perPage }}
                    </a>
                  @endunless
                @endforeach
              </div>
            </div>
          </div>

          @if ($movies instanceof Illuminate\Pagination\LengthAwarePaginator)
            {{ $movies->appends($params)->links() }}

            <span class="ui pointing basic label float-right">
              Total: {{ $movies->total() }}
            </span>
          @endif
        </div>
      </div>
    </div>

    <script src="{{ mix('js/movie-libs.js') }}"></script>
    <script src="{{ mix('js/movie.js') }}"></script>
    <script>
      $('.ui.star.rating').rating('disable')

      $('.ui.search.dropdown.genre').dropdown({
        allowAdditions: true,
        hideAdditions: false,
        forceSelection: false,
        placeholder: false,
        action: (text, value) => {
          $("input[name=genre]").val(value)
          $('#form').submit()
        }
      })

      $('.ui.search.dropdown.year').dropdown({
        forceSelection: false,
        placeholder: false,
        action: (text, value) => {
          $("input[name=year]").val(value)
          $('#form').submit()
        }
      })

      $('.ui.selection.dropdown.rate').dropdown({
        action: (text, value) => {
          $("input[name=rating]").val(value)
          $('#form').submit()
        }
      })
    </script>
  </body>
</html>
