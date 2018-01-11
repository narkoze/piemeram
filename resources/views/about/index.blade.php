@extends('layout')
@section('content')
<h1>@lang('about.title')</h1>

<div class="ui piled segment">
  <img
    class="ui medium rounded image left floated"
    src="{{ asset('img/profile.jpg') }}"
  >
  <h1 class="ui header">Edgars Vanags</h1>
  <h3>Personas dati</h3>
  <p>
    Man ir <b>{{ $age }}</b> gadi
    <br>
    Dzīvoju es <b>Liepājā</b>
    <br>
    Sazināties ar mani var <a href="mailto:ev@piemeram.lv">ev@piemeram.lv</a>
  </p>
  <br><br><br><br><br>
</div>
@endsection
