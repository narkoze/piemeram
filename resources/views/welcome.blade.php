@extends('layout')
@section('content')
<h1>@lang('welcome.title')</h1>

<div class="ui card">
  <div class="content">
    <div class="header">
      @lang('welcome.programming.title')
    </div>
    <div class="description">
      @foreach (collect(Lang::get('welcome.programming'))->except('title') as $value)
        <p>{{ $value }}</p>
      @endforeach
    </div>
  </div>
</div>

<div id="appElement">
  <piemeram-example
    text="Vue"
  >
  </piemeram-example>
</div>
@endsection

@section('scripts')
  <script src="{{ mix('js/components/example/example.js') }}"></script>

  <script>
    new Vue({
      el: '#appElement'
    })
  </script>
@endsection
