<input type="hidden" name="genres" value="{{ $movie->genres ?? null }}">

<div class="
  field
  @if ($errors->has('name'))
    error
  @endif
">
  <label>Name</label>
  <input
    type="text"
    name="name"
    value="{{ old('name', $movie->name ?? null) }}"
  >
  @if ($errors->has('name'))
    <small class="ui red">
      {{ $errors->first('name') }}
    </small>
  @endif
</div>

<div class="
  field
  @if ($errors->has('genres'))
    error
  @endif
">
  <label>Genres</label>
  <select
    class="ui fluid search dropdown genres"
    multiple=""
  >
    @foreach ($genres as $genre)
      <option value="{{ $genre }}">
        {{ $genre }}
      </option>
    @endforeach
  </select>
  @if ($errors->has('genres'))
    <small class="ui red">
      {{ $errors->first('genres') }}
    </small>
  @endif
</div>

<div class="ui stackable three column grid">
  <div class="column field">
    <div class="
      field
      @if ($errors->has('year'))
        error
      @endif
    ">
      <label>Year</label>
      <input
        type="number"
        name="year"
        value="{{ old('year', $movie->year ?? 1850) }}"
        min="1850"
      >
      @if ($errors->has('year'))
        <small class="ui red">
          {{ $errors->first('year') }}
        </small>
      @endif
    </div>
  </div>

  <div class="column field">
    <div class="
      field
      @if ($errors->has('rating'))
        error
      @endif
    ">
      <label>Rating</label>
      <input
        type="number"
        name="rating"
        value="{{ old('rating', $movie->rating ?? 0) }}"
        step="0.1"
        max="10"
        min="0"
      >
      @if ($errors->has('rating'))
        <small class="ui red">
          {{ $errors->first('rating') }}
        </small>
      @endif
    </div>
  </div>

  <div class="column field">
    <div class="
      field
      @if ($errors->has('votes'))
        error
      @endif
    ">
      <label>Votes</label>
      <input
        type="number"
        name="votes"
        value="{{ old('votes', $movie->votes ?? 0) }}"
        min="0"
      >
      @if ($errors->has('votes'))
        <small class="ui red">
          {{ $errors->first('votes') }}
        </small>
      @endif
    </div>
  </div>
</div>

<div class="
  field
  @if ($errors->has('imdb'))
    error
  @endif
">
  <label>Imdb link</label>
  <input
    type="text"
    name="imdb"
    value="{{ old('imdb', $movie->imdb ?? 'http://www.') }}"
  >
  @if ($errors->has('imdb'))
    <small class="ui red">
      {{ $errors->first('imdb') }}
    </small>
  @endif
</div>

@push('scripts')
  <script>
    $('input[name="year"]').attr('max', new Date().getFullYear())

    let genres = @json(old('genres', $movie->genres ?? '')) || '';
    $('.ui.search.dropdown.genres')
      .dropdown('set selected', genres.split(', '))
      .dropdown({
        onChange: value => {
          setGenres(value)
        }
      })

    function setGenres (genres) {
      $('input[name="genres"]').val(genres.join(', '))
    }

    setGenres($('.ui.search.dropdown.genres').dropdown('get value'))
  </script>
@endpush
