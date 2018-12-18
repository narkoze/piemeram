@extends('layout')
@section('content')
  <h1>@lang('about.index.title')</h1>

  <div class="ui piled segment">
    <div class="ui items">
      <h2>Edgars Vanags</h2>

      <div class="item">
        <div class="image">
          <img src="{{ asset('img/profile.jpg') }}">
        </div>
        <div class="content">
          <h3>@lang('about.index.personaldata')</h3>
          <h6></h6>
          <div class="description">
            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.age')
              </div>
              <div class="ten wide column">
                {{ $age }}
              </div>
            </div>

            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.birthday')
              </div>
              <div class="ten wide column">
                11. @lang('about.index.april') 1989
              </div>
            </div>

            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.address')
              </div>
              <div class="ten wide column">
                Liepāja, @lang('about.index.latvia')
              </div>
            </div>

            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.email')
              </div>
              <div class="ten wide column">
                <a href="mailto:ev@piemeram.lv">ev@piemeram.lv</a>
              </div>
            </div>

            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.educationlevel')
              </div>
              <div class="ten wide column">
                @lang('about.index.secondaryeducation')
              </div>
            </div>

            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.currentpositionstate')
              </div>
              <div class="ten wide column">
                @lang('about.index.working')
              </div>
            </div>

            <div class="ui grid">
              <div class="five wide column">
                @lang('about.index.links')
              </div>
              <div class="ten wide column">
                <a
                  href="http://www.facebook.com/edgars.vanags.9"
                  title="@lang('about.index.facebookprofile')"
                  target="_blank"
                >
                  <img
                    class="social"
                    src="{{ asset('img/facebook.jpg') }}"
                    width="14"
                  >
                </a>

                <a
                  href="http://indorse.io/narkoze"
                  title="@lang('about.index.indorse')"
                  target="_blank"
                >
                  <img
                    class="social"
                    src="{{ asset('img/indorse.jpg') }}"
                    width="14"
                  >
                </a>

                <a
                  href="http://www.cv.lv/CVC/5800602.html"
                  title="@lang('about.index.cv')"
                  target="_blank"
                >
                  <img
                    class="social"
                    src="{{ asset('img/cv.jpg') }}"
                    width="14"
                  >
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <h3>@lang('about.index.workexperience')</h3>

    <div class="ui grid">
      <div class="five wide column">
        <b>2016/06 @lang('about.index.tilltoday')</b>
      </div>
      <div class="four wide column">
        <a href="http://company.lursoft.lv/en/ae-partner/52103031051">
          "SIA AE Partner"
        </a>
      </div>
      <div class="seven wide column">
        <b>@lang('about.index.position')</b>
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.responsabilitiestasks')
      </div>
      <div class="ten wide column">
        @lang('about.index.programmingit')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.companydata')
      </div>
      <div class="eleven wide column">
        @lang('about.index.companydescription.0')
      </div>
    </div>

    <h6></h6>

    <div class="ui grid">
      <div class="five wide column">
        <b>2008/12 - 2015/08</b>
      </div>
      <div class="four wide column">
        <a href="https://company.lursoft.lv/baltik-it/40003999164">
          "SIA BALTIK IT"
        </a>
      </div>
      <div class="seven wide column">
        <b>@lang('about.index.dataentryoperator')</b>
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.responsabilitiestasks')
      </div>
      <div class="ten wide column">
        @lang('about.index.dataentryapps')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.companydata')
      </div>
      <div class="eleven wide column">
        @lang('about.index.companydescription.1')
      </div>
    </div>


    <h3>@lang('about.index.languageknowledge')</h3>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.nativelanguage')
      </div>
      <div class="ten wide column">
        @lang('about.index.latvian')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column"></div>
      <div class="two wide column"><b>@lang('about.index.dialog')</b></div>
      <div class="two wide column"><b>@lang('about.index.monologue')</b></div>
      <div class="two wide column"><b>@lang('about.index.reading')</b></div>
      <div class="two wide column"><b>@lang('about.index.listening')</b></div>
      <div class="two wide column"><b>@lang('about.index.writing')</b></div>
    </div>

    <div class="ui grid">
      <div class="five wide column"><b>@lang('about.index.english')</b></div>
      <div class="two wide column">@lang('about.index.b1')</div>
      <div class="two wide column">@lang('about.index.b1')</div>
      <div class="two wide column">@lang('about.index.b2')</div>
      <div class="two wide column">@lang('about.index.b2')</div>
      <div class="three wide column">@lang('about.index.b1')</div>
    </div>

    <div class="ui grid">
      <div class="five wide column"><b>@lang('about.index.russian')</b></div>
      <div class="two wide column">@lang('about.index.b2')</div>
      <div class="two wide column">@lang('about.index.b2')</div>
      <div class="two wide column">@lang('about.index.c1')</div>
      <div class="two wide column">@lang('about.index.c1')</div>
      <div class="three wide column">@lang('about.index.a2')</div>
    </div>


    <h3>@lang('about.index.computerknowledge')</h3>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.basiclevel')
      </div>
      <div class="ten wide column">
        CSS/SASS (Zurb Foundation, Semantic UI), C#, AHK, ZPL2
      </div>
    </div>

    <h6></h6>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.mediumlevel')
      </div>
      <div class="eleven wide column">
        HTML<br>
        JavaScript (VueJs)<br>
        PHP (Laravel)<br>
        PostgreSQL<br>
        Git<br>
        Windows 7/10
      </div>
    </div>

    <h6></h6>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.addinfocomputerskills')
      </div>
      <div class="ten wide column">
        @lang('about.index.basicinfo')
        <br>
        @lang('about.index.activeinfo')
        <br>
        @lang('about.index.passiveinfo')
        <br>
        @lang('about.index.workorganization')
      </div>
    </div>

    <br><br>

    <a
      class="ui button red"
      href="{{ route('about', ['pdf' => 'show']) }}"
      target="pdf"
    >
      @lang('system.openaspdf')
    </a>
  </div>
@endsection
