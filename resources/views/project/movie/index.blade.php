@extends('project.movie.layout')

@section('subtitle')
  Movies
@endsection

@section('breadcrumb')
  <a
    href="{{ route('movie.index') }}"
    class="active section"
  >
    Movies
  </a>
@endsection

@section('content')
  <div class="ui stackable grid form">
    <div class="three wide column">
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
          href="{{ route('movie.index') }}?{{ http_build_query([
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

    <div class="ten wide column">
      <form
        action="{{ route('movie.index') }}"
        id="form"
        class="field"
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
        <input
          value="{{ $params['onlySelected'] }}"
          type="hidden"
          name="onlySelected"
        >
        <input
          value="{{ $params['checked'] }}"
          type="hidden"
          name="checked"
        >
        <input
          value="{{ $params['unchecked'] }}"
          type="hidden"
          name="unchecked"
        >
        <input
          value="{{ $params['sortBy'] }}"
          type="hidden"
          name="sortBy"
        >
        <input
          value="{{ $params['sortDirection'] }}"
          type="hidden"
          name="sortDirection"
        >

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
              href="{{ route('movie.index') }}?{{ http_build_query([
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

        @php
          $checkedRows = array_filter(explode(',', $params['checked']));
          $uncheckedRows = array_filter(explode(',', $params['unchecked']));
        @endphp

        <table class="ui selectable celled table">
          <thead>
            <tr>
              <th>
                <div class="ui fitted read-only checkbox">
                  <input
                    type="checkbox"
                    @if ($params['checked'] == 'all' or $params['onlySelected'])
                      checked
                    @endif
                  >
                  <label
                    onclick="location = '{{ route('movie.index') }}?{{ http_build_query([
                      'checked' => ($params['checked'] == 'all' or $params['onlySelected']) ? '' : 'all',
                      'unchecked' => null,
                      'onlySelected' => false,
                    ] + $params) }}'"
                  >
                  </label>
                </div>
                <div class="field">
              </th>

              <th class="nowrap fullwidth">
                <a
                  href="{{ route('movie.index') }}?{{ http_build_query([
                    'sortBy' => 'name',
                    'sortDirection' => $params['sortBy'] == 'name' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                    'page' => 1,
                  ] + $params) }}"
                >
                  Name
                  @if ($params['sortBy'] == 'name')
                    <i class="fitted angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                  @endif
                </a>
                @if ($search)
                  <a
                    href="{{ route('movie.index') }}?{{ http_build_query([
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
                  href="{{ route('movie.index') }}?{{ http_build_query([
                    'sortBy' => 'genres',
                    'sortDirection' => $params['sortBy'] == 'genres' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                    'page' => 1,
                  ] + $params) }}"
                >
                  Genres
                  @if ($params['sortBy'] == 'genres')
                    <i class="fitted angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                  @endif
                </a>
                @if ($params['genre'])
                  <a
                    href="{{ route('movie.index') }}?{{ http_build_query([
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
                  href="{{ route('movie.index') }}?{{ http_build_query([
                    'sortBy' => 'year',
                    'sortDirection' => $params['sortBy'] == 'year' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                    'page' => 1,
                  ] + $params) }}"
                >
                  Year
                  @if ($params['sortBy'] == 'year')
                    <i class="fitted angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                  @endif
                </a>
                @if ($params['year'])
                  <a
                    href="{{ route('movie.index') }}?{{ http_build_query([
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
                  href="{{ route('movie.index') }}?{{ http_build_query([
                    'sortBy' => 'rating',
                    'sortDirection' => $params['sortBy'] == 'rating' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                    'page' => 1,
                  ] + $params) }}"
                >
                  Rating
                  @if ($params['sortBy'] == 'rating')
                    <i class="fitted angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                  @endif
                </a>
                @if ($params['rating'])
                  <a
                    href="{{ route('movie.index') }}?{{ http_build_query([
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

              <th class="nowrap">
                <a
                  href="{{ route('movie.index') }}?{{ http_build_query([
                    'sortBy' => 'votes',
                    'sortDirection' => $params['sortBy'] == 'votes' ? ($params['sortDirection'] == 'desc' ? 'asc' : 'desc') : 'asc',
                    'page' => 1,
                  ] + $params) }}"
                >
                  Votes
                  @if ($params['sortBy'] == 'votes')
                    <i class="fitted angle icon {{ $params['sortDirection'] == 'asc' ? 'up' : 'down' }}"></i>
                  @endif
                </a>
              </th>

              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($movies as $movie)
              <tr>
                <td>
                  <div class="ui fitted read-only checkbox">
                    <input
                      type="checkbox"
                      @if (
                        in_array($movie->id, $checkedRows) or
                        ($params['checked'] == 'all' and !in_array($movie->id, $uncheckedRows))
                      )
                        checked
                      @endif
                    >
                    <label
                      @if ($params['checked'] == 'all')
                        onclick="location = '{{ route('movie.index') }}?{{ urldecode(http_build_query([
                          'unchecked' => call_user_func(function () use ($uncheckedRows, $movie) {
                            if (in_array($movie->id, $uncheckedRows)) {
                              $uncheckedRows = array_diff($uncheckedRows, [$movie->id]);
                            } else {
                              $uncheckedRows[] = $movie->id;
                            }
                            return implode(',', $uncheckedRows);
                          }),
                          'onlySelected' => $params['checked'] ? $params['onlySelected'] : false
                        ] + $params)) }}'"
                      @else
                        onclick="location = '{{ route('movie.index') }}?{{ urldecode(http_build_query([
                          'checked' => call_user_func(function () use ($checkedRows, $movie) {
                            if (in_array($movie->id, $checkedRows)) {
                              $checkedRows = array_diff($checkedRows, [$movie->id]);
                            } else {
                              $checkedRows[] = $movie->id;
                            }
                            return implode(',', $checkedRows);
                          }),
                          'onlySelected' => count($checkedRows) > 1 ? $params['onlySelected'] : false
                        ] + $params)) }}'"
                      @endif
                    >
                    </label>
                  </div>
                </td>

                <td>
                  <a href="{{ route('movie.edit', $movie) }}">
                    <b>
                      @if ($search)
                        {!! preg_replace("/($search)/i", "<u class='highlight'>$0</u>", $movie->name) !!}
                      @else
                        {{ $movie->name }}
                      @endif
                    </b>
                  </a>
                  <br>
                  <a
                    href="{{ $movie->imdb }}"
                    target="_blank"
                    class="ui black"
                  >
                    <small>{{ $movie->imdb }}</small>
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
                        href="{{ route('movie.index') }}?{{ http_build_query([
                          'genre' => $genre,
                          'page' => 1,
                        ] + $params) }}"
                        data-tooltip="Filter by {{ $genre }}"
                        data-inverted=""
                        class="nowrap ui black"
                      >
                        <u>{!! preg_replace("/(". trim($params['genre']) .")/i", "<u class='highlight'>$0</u>", $genre) !!}</a></u>@unless ($loop->last), @endunless
                    @endif
                  @endforeach
                </td>

                <td>
                  @if ($params['year'])
                    {{ $movie->year }}
                  @else
                    <a
                      href="{{ route('movie.index') }}?{{ http_build_query([
                        'year' => $movie->year,
                        'page' => 1,
                      ] + $params) }}"
                      data-tooltip="Filter by {{ $movie->year }}"
                      data-inverted=""
                      class="ui black"
                    >
                      <u>
                        {{ $movie->year }}
                      </u>
                    </a>
                  @endif
                </td>

                <td class="nowrap">
                  {{ number_format($movie->rating, 1) }}

                  @php
                    $stars = round($movie->rating);
                  @endphp

                  <a
                    @unless ($params['rating'])
                      href="{{ route('movie.index') }}?{{ http_build_query([
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

                <td>
                  <div class="ui dropdown row">
                    <i class="bars icon"></i>
                    <div class="menu">
                      <a
                        href="{{ route('movie.edit', $movie) }}"
                        class="item"
                      >
                        Edit
                      </a>
                      <a
                        href="{{ route('movie.destroy', $movie) }}"
                        class="item"
                        data-confirm="Delete {{ $movie->name }}?"
                        data-method="delete"
                      >
                        <span class="ui red">Delete</span>
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($movies instanceof Illuminate\Pagination\LengthAwarePaginator)
        {{ $movies->appends($params)->onEachSide(1)->links() }}

        @if ($movies->lastPage() > 1)
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
                      href="{{ route('movie.index') }}?{{ http_build_query([
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
        @endif
      @endif

      <span class="ui pointing basic label float-right">
        Total:
        @if ($movies instanceof Illuminate\Pagination\LengthAwarePaginator)
          {{ $movies->total() }}
        @else
          {{ $movies->count() }}
        @endif
      </span>
    </div>

    <div class="three wide column">
      <div class="field">
        <label>&nbsp;</label>
        <a
          href="{{ route('movie.create') }}"
          class="ui fluid primary button"
        >
          <i class="plus icon"></i>
          Add movie
        </a>
      </div>

      <div class="field">
        <div class="ui dropdown button fluid bulk
          @unless ($params['checked'] or $params['unchecked'])
            disabled
          @endunless
        ">
          <div class="text">
            @php
              if ($params['checked'] == 'all') {
                $count = $movies instanceof Illuminate\Pagination\LengthAwarePaginator
                  ? $movies->total()
                  : $movies->count();

                $selected = $count - ($params['onlySelected'] ? 0 : count($uncheckedRows));
              } else {
                $selected = count($checkedRows);
              }
            @endphp
            Selected: {{ $selected }}
          </div>

          <div class="menu">
            <a
              href="{{ route('movie.index') }}?{{ http_build_query([
                'checked' => '',
                'unchecked' => '',
                'onlySelected' => false,
              ] + $params) }}"
              class="item"
            >
              Deselect all
            </a>

            @if ($params['onlySelected'])
              <a
                href="{{ route('movie.index') }}?{{ http_build_query([
                  'onlySelected' => false,
                  'page' => 1,
                ] + $params) }}"
                class="item"
              >
                Show all
              </a>
            @else
              <a
                href="{{ route('movie.index') }}?{{ http_build_query([
                  'onlySelected' => true,
                  'page' => 1,
                ] + $params) }}"
                class="item"
              >
                Show selected only
              </a>
            @endif

            <a
              href="{{ route('movie.destroy.multiple') }}?{{ http_build_query($params) }}"
              class="item"
              data-confirm="Delete {{ $selected }} movies?"
              data-method="delete"
            >
              <span class="ui red">Delete selected</span>
            </a>
          </div>

          <i class="dropdown icon"></i>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $('.ui.star.rating').rating('disable')
    $('.ui.checkbox').checkbox()
    $('.ui.dropdown.row').dropdown()
    $('.ui.dropdown.bulk').dropdown()

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
@endsection
