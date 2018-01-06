@extends('layout')
@section('content')
<h1>@lang('welcome.title')</h1>

<h5 class="ui top attached header">@lang('welcome.programming.title')</h5>
<div class="ui attached segment">
  @foreach (collect(Lang::get('welcome.programming'))->except('title') as $value)
    <p>{{ $value }}</p>
  @endforeach
</div>
@endsection
