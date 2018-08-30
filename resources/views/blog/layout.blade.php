<!doctype html>
<html lang="EN">
  <head>
    <title>@lang('blog/layout.title')</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">
    @include('shared.php2js.laravel')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ mix('css/blog.css') }}" />
  </head>
  <body class="has-navbar-fixed-top">
    <div id="appElement">
      <piemeram-blog-window :auth-user="{{ json_encode($auth) }}"></piemeram-blog-window>
    </div>

    <script src="{{ mix('js/libs.js') }}"></script>
    <script src="{{ mix('js/vue.js') }}"></script>
    <script src="{{ mix('js/components/blog/blog.js') }}"></script>
    <script>
      new Vue({
        el: '#appElement'
      })
    </script>
  </body>
</html>
