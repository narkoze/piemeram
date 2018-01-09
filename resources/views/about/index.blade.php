@extends('layout')
@section('content')
<h1>@lang('about.title')</h1>

<div class="ui piled segment">
  <img
    class="ui medium rounded image left floated"
    src="{{ asset('img/profile.jpg') }}"
  >
  <h1 class="ui header">Edgars Vanags</h1>
  <p>
    Liepāja, Liepāja, Liepājas rajons, Latvija
  </p>
  <br><br><br><br><br><br><br><br><br><br>
</div>
@endsection
