<div class="ui pointing stackable menu">
  <a
    class="ui red label header item"
    href="{{ action('HomeController@index') }}"
  >
    {{ config('app.name') }}
  </a>

  <a
    class="
      item
      @if ($tab == 'home')
        active
      @endif
    "
    href="{{ action('HomeController@index') }}"
  >
    @lang('menu.home')
  </a>

  <a
    class="
      item
      @if ($tab == 'project')
        active
      @endif
    "
    href="{{ action('ProjectController@index') }}"
  >
    @lang('menu.project')
  </a>

  <a
    class="
      item
      @if ($tab == 'about')
        active
      @endif
    "
    href="{{ action('AboutController@index') }}"
  >
    @lang('menu.about')
  </a>

  <div class="right menu">
    <div class="borderless item">
      @lang('menu.greetings')
    </div>
    <div class="ui dropdown item">
      <i class="fa fa-language"></i>&nbsp;{{ strtoupper(app()->getLocale()) }}
      <div class="menu">
        @foreach([
          'lv' => 'LV',
          'en' => 'EN',
        ] as $key => $label)
          @unless (app()->getLocale() == $key)
            <a
              class="item"
              href="{{ action('LocaleController@setLocale', $key) }}"
              data-method="post"
              data-remote="true"
            >
              {{ $label }}
            </a>
          @endunless
        @endforeach
      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    window.$('.ui.dropdown').dropdown()
  </script>
@endpush
