@extends('project.movie.layout')

@section('subtitle')
  Edit {{ $movie->name }}
@endsection

@section('breadcrumb')
  <a
    href="{{ route('movie.index') }}"
    class="section"
  >
    Movies
  </a>
  <i class="right angle icon divider"></i>
  <a
    href="{{ route('movie.edit', $movie) }}"
    class="active section"
  >
    Edit {{ $movie->name }}
  </a>
@endsection

@section('content')
  <div class="ui stackable grid form">
    <div class="three wide column"></div>

    <div class="eleven wide column">
      <form
        action="{{ route('movie.update', $movie) }}"
        method="post"
        class="ui form"
      >
        @csrf
        <input type="hidden" name="_method" value="PUT">

        @include('project.movie.shared.form')

        <div class="ui grid">
          <div class="sixteen wide column">
            <button
              type="submit"
              class="ui primary button"
            >
              Edit
            </button>

            <a
              href="{{ route('movie.destroy', $movie) }}"
              class="ui red right floated button"
              data-confirm="Delete {{ $movie->name }}?"
              data-method="delete"
            >
              Delete
            </a>
          </div>
        </div>
      </form>
    </div>

    <div class="two wide column"></div>
  </div>
@endsection

@section('scripts')
  @stack('scripts')
@endsection
