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
    <script>
      window.auth = @json($auth);
      @if (isset($post))
        window.post = @json($post);
      @endif
    </script>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ mix('css/blog.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome-animation.min.css') }}" />
  </head>
  <body class="has-navbar-fixed-top">
    <div class="notification"></div>

    <div id="blog">
      <piemeram-blog-window></piemeram-blog-window>
    </div>

    <script src="{{ mix('js/libs.js') }}"></script>
    <script src="{{ mix('js/vue.js') }}"></script>
    <script src="{{ mix('js/components/blog/blog.js') }}"></script>
  </body>
</html>
