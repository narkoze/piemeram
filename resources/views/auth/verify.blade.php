@extends('layout')
@section('content')
  <h1>@lang('auth.verify.title')</h1>

  <div class="ui segment">
    @if (session('resent'))
      @lang('auth.verify.emailsent')
    @else
      <p>@lang('auth.verify.beforeproceeding')</p>

      <a
        href="{{ route('verification.resend') }}"
        class="ui button red"
      >
        @lang('auth.verify.resend')
      </a>
    @endif
  </div>
@endsection

