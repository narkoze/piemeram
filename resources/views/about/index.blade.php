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
                    width="14"
                    src="{{ asset('img/facebook.jpg') }}"
                  >
                </a>
                <a
                  href="http://www.cv.lv/CVC/5800602.html"
                  title="@lang('about.index.cv')"
                  target="_blank"
                >
                  <img
                    class="social"
                    width="14"
                    src="{{ asset('img/cv.jpg') }}"
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
        <b>"SIA AE Partner"</b>
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
        <b>"SIA BALTIK IT"</b>
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


    <h3>@lang('about.index.futureworkrequirements')</h3>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.desiredworkingtime')
      </div>
      <div class="ten wide column">
        @lang('about.index.fulltime')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.location')
      </div>
      <div class="ten wide column">
        Liepāja
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.canstartwork')
      </div>
      <div class="ten wide column">
        @lang('about.index.byagreement')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.monthlysalary')
      </div>
      <div class="ten wide column">
        @lang('about.index.aboutsalary', ['euro' => '750.00'])
      </div>
    </div>


    <h3>@lang('about.index.drivinglicense')</h3>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.category')
      </div>
      <div class="ten wide column">
        B ({{ $driving }} @lang('about.index.years'))
      </div>
    </div>


    <h3>@lang('about.index.addinfo')</h3>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.personalcharacteristics')
      </div>
      <div class="ten wide column">
        @lang('about.index.aboutpersonalcharacteristics')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.pluses')
      </div>
      <div class="ten wide column">
        @lang('about.index.aboutpluses')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.flaws')
      </div>
      <div class="ten wide column">
        @lang('about.index.aboutflaws')
      </div>
    </div>

    <div class="ui grid">
      <div class="five wide column">
        @lang('about.index.interestshobbies')
      </div>
      <div class="ten wide column">
        @lang('about.index.aboutinterestshobbies')
      </div>
    </div>

    <br><br>
  </div>
@endsection
