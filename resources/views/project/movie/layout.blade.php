<!doctype html>
<html lang="EN">
  <head>
    <title>Piemeram.lv</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ mix('css/movie.css') }}" />
  </head>
  <body>
    <div class="ui container">
      @if (session('notification'))
        <div class="ui green icon message">
          <i class="check icon"></i>
          <i class="close icon"></i>

          <div class="content">
            <div class="header">
              {{ session('notification') }}
            </div>
          </div>
        </div>
      @endif

      <h1 class="ui header">
        Multifunctional table
        <div class="sub header">
          @yield('subtitle')
        </div>
      </h1>

      <div class="ui breadcrumb">
        @yield('breadcrumb')
      </div>

      @yield('content')
    </div>

    <script src="{{ mix('js/movie-libs.js') }}"></script>
    <script src="{{ mix('js/movie.js') }}"></script>

    <script>
      $('.message .close').on('click', function () {
        $(this)
          .closest('.message')
          .remove()
      })
    </script>

    @yield('scripts')
  </body>
</html>
