@extends('project.movie.layout')

@section('subtitle')
  Add new
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
    href="{{ route('movie.create') }}"
    class="active section"
  >
    Add new
  </a>
@endsection

@section('content')
  <div class="ui stackable grid">
    <div class="three wide column"></div>

    <div class="eleven wide column">
      <form
        action="{{ route('movie.store') }}"
        method="post"
        class="ui form"
      >
        @csrf

        @include('project.movie.shared.form')

        <div class="ui grid">
          <div class="sixteen wide column">
            <button
              type="submit"
              class="ui primary button"
            >
              Add
            </button>
          </div>
        </div>
      </form>
    </div>

    <div class="two wide column"></div>
  </div>
@endsection

@section('scripts')
  @stack('scripts')

  <script>
    $('input[name="name"]').focus()
  </script>
@endsection
