@extends('message.layout')
@section('content')

  @lang('message.passwordreset.content')

  @component('mail::button', [
      'url' => url(config('app.url').route('password.reset', [$token, "email=$email"], false)),
      'color' => 'red'
  ])
    @lang('message.passwordreset.button')
  @endcomponent

  @lang('message.passwordreset.info')

@endsection
