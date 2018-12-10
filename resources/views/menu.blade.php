<div class="ui pointing stackable menu">
  <a
    class="ui red label header item"
    href="{{ route('home') }}"
  >
    Piemeram
  </a>

  {{-- <a
    class="
      item
      @if (isset($tab) and $tab == 'home')
        active
      @endif
    "
    href="{{ route('home') }}"
  >
    @lang('menu.home')
  </a> --}}

  <a
    class="
      item
      @if (isset($tab) and $tab == 'project')
        active
      @endif
    "
    href="{{ route('project') }}"
  >
    @lang('menu.project')
  </a>

  <a
    class="
      item
      @if (isset($tab) and $tab ==  'about')
        active
      @endif
    "
    href="{{ route('about') }}"
  >
    @lang('menu.about')
  </a>

    <div class="right menu">
      <div class="item borderless">
        <div id="appElement">
          @guest
            <a
              href=""
              @click.prevent="showLogin = true"
            >
              @lang('menu.login')
            </a>
            <piemeram-modal
              :show="showLogin"
              @close="showLogin = false"
              size="tiny"
            >
              <piemeram-login @loggedin="refresh" @close="showLogin = false"></piemeram-login>
            </piemeram-modal>
          @endguest
        </div>
        @auth
          <div>
            @lang('menu.greeting'),
            <div class="ui dropdown">
              <a href="">{{ auth()->user()->name }}</a>
              <div class="menu">
                  @verified (false)
                  <a
                    class="item"
                    href="{{ route('verification.notice') }}"
                  >
                    @lang('menu.verify')
                  </a>
                  @endverified
                  <a
                    class="item"
                    href="{{ route('logout') }}"
                    data-method="post"
                  >
                    @lang('menu.logout')
                  </a>
              </div>

              @verified (false)
                <i
                  class="fa fa-exclamation-triangle"
                  title="{{ trans('menu.verificationrequired') }}"
                >
                </i>
              @endverified
            </div>
          </div>
        @endauth
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
                href="{{ route('setLocale', $key) }}"
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
  <script src="{{ mix('js/components/modal/modal.js') }}"></script>
  <script src="{{ mix('js/components/login/login.js') }}"></script>

  <script>
    window.$('.ui.dropdown').dropdown()

    new Vue({
      el: '#appElement',
      data: {
        showLogin: false
      },
      methods: {
        refresh: function () {
          window.location.reload()
        }
      }
    })
  </script>
@endpush
