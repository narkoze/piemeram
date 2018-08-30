<!doctype html>
<html lang="EN">
  <head>
    <title>Piemeram.lv</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">
    @include('shared.php2js.laravel')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome-animation.min.css') }}" />
  </head>
  <body>
    <div class="ui container">
      @include('menu')
      @yield('content')
    </div>
    @include('github')
    <script src="{{ mix('js/libs.js') }}"></script>
    <script src="{{ mix('js/vue.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
  </body>
</html>
