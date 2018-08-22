@extends('message.layout')
@section('content')

  @lang('message.resetpassword.content')

  @component('mail::button', [
      'url' => url(config('app.url').route('password.reset', [$token, "email=$email"], false)),
      'color' => 'red'
  ])
    @lang('message.resetpassword.resetbutton')
  @endcomponent

  @lang('message.resetpassword.info')

@endsection