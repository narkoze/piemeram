@extends('layout')
@section('content')
  <div class="ui segment">
    <div class="ui stackable three column grid">
      <div class="column">
        <h1>@lang('auth.passwords.reset.title')</h1>

        <form
          action="{{ route('password.request') }}"
          method="post"
          class="ui form"
        >
          <input type="hidden" name="token" value="{{ $token }}">
          @csrf
      
          <div class="field @if ($errors->has('email')) error @endif">
            <label>@lang('auth.passwords.reset.email')
              <input
                type="email"
                name="email"
                value="{{ $email ?? old('email') }}"
                required
              >
            </label>
            @if ($errors->has('email')) <small class="error">{{ implode(' ', $errors->get('email')) }}</small> @endif
          </div>
      
          <div class="field @if ($errors->has('password')) error @endif">
            <label>@lang('auth.passwords.reset.password')
              <input
                type="password"
                name="password"
                required
                autofocus
              >
            </label>
            @if ($errors->has('password')) <small class="error">{{ implode(' ', $errors->get('password')) }}</small> @endif
          </div>
      
          <div class="field">
            <label>@lang('auth.passwords.reset.passwordagain')
              <input
                type="password"
                name="password_confirmation"
                required
              >
            </label>
          </div>
      
          <button class="ui button red" type="submit">@lang('system.reset')</button>
        </form>
      </div>
    </div>
  </div>
@endsection
