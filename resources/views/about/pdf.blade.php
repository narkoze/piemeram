<!doctype html>
<html>
  <head>
    <title>{{ $title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
      @page {
        size: a4 portrait;
        margin: 1cm 2cm 2cm 2cm;
      }
      @font-face {
        font-family: 'Roboto';
        font-weight: normal;
        font-style: normal;
        src: url("{{ public_path('pdf_fonts/Roboto/Roboto-Regular.ttf') }}") format('truetype');
      }
      @font-face {
        font-family: 'Roboto';
        font-weight: bold;
        font-style: normal;
        src: url("{{ public_path('pdf_fonts/Roboto/Roboto-Bold.ttf') }}") format('truetype');
      }
      body {
        font-family:"Roboto";
        font-size: 14px;
      }
      img.profile {
        width: 180px;
      }
      a {
        text-decoration: none;
        color: #3C579A;
      }
      a.red {
        color: #DB2828;
      }
      h1 {
        margin-bottom: 2px;
      }
      h2 {
        text-align: left;
        margin: 17px 0 4px 0;
      }
      table {
        border-collapse: collapse;
      }
      table.fullwidth {
        width: 100%;
      }
      th.height {
        height: 20px;
        vertical-align: bottom;
      }
      td {
        vertical-align: top;
      }
      footer {
        border-top: 1px solid black;
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 50px;
        text-align: center;
        line-height: 35px;
      }
    </style>
  </head>
  <body>
    <table class="fullwidth">
      <tr>
        <td colspan="2">
          <h1>Edgars Vanags</h1>
        </td>
      </tr>

      <tr>
        <td style="width:200px">
          <img
            class="profile"
            src="file://{{ public_path('img/profile.jpg') }}"
          >
        </td>
        <td>
          <table class="fullwidth">
            <tr>
              <th colspan="2">
                <h2>@lang('about.index.personaldata')</h2>
              </th>
            </tr>

            <tr>
              <td style="width: 180px">
                @lang('about.index.age')
              </td>
              <td>{{ $age }}</td>
            </tr>

            <tr>
              <td>@lang('about.index.birthday')</td>
              <td>11. @lang('about.index.april') 1989</td>
            </tr>

            <tr>
              <td>@lang('about.index.address')</td>
              <td>Liepāja, @lang('about.index.latvia')</td>
            </tr>

            <tr>
              <td>@lang('about.index.email')</td>
              <td>
                <a
                  class="red"
                  href="mailto:ev@piemeram.lv"
                >
                  ev@piemeram.lv
                </a>
              </td>
            </tr>

            <tr>
              <td>@lang('about.index.educationlevel')</td>
              <td>@lang('about.index.secondaryeducation')</td>
            </tr>

            <tr>
              <td>@lang('about.index.currentpositionstate')</td>
              <td>@lang('about.index.working')</td>
            </tr>

            <tr>
              <td>@lang('about.index.links')</td>
              <td>
                <a href="http://www.facebook.com/edgars.vanags.9">
                  <img
                    src="file://{{ public_path('img/facebook.jpg') }}"
                    width="14"
                  > facebook.com/edgars.vanags.9
                </a>

                <br>

                <a href="http://www.cv.lv/CVC/5800602.html">
                  <img
                    src="file://{{ public_path('img/cv.jpg') }}"
                    width="14"
                  > cv.lv/CVC/5800602.html
                </a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <table class="fullwidth">
      <tr>
        <td colspan="6">
          <h2>@lang('about.index.workexperience')</h2>
        </td>
      </tr>

      <tr>
        <th style="width: 154px">2016/06 @lang('about.index.tilltoday')</th>
        <th colspan="2">"SIA AE Partner"</th>
        <th colspan="3">@lang('about.index.position')</th>
      </tr>

      <tr>
        <td>@lang('about.index.responsabilitiestasks')</td>
        <td colspan="5">@lang('about.index.programmingit')</td>
      </tr>

      <tr>
        <td>@lang('about.index.companydata')</td>
        <td colspan="5">@lang('about.index.companydescription.0')</td>
      </tr>

      <tr>
        <th class="height">2008/12 - 2015/08</th>
        <th
          class="height"
          colspan="2"
        >
          "SIA BALTIK IT"
        </th>
        <th
          class="height"
          colspan="3"
        >
          @lang('about.index.dataentryoperator')
        </th>
      </tr>

      <tr>
        <td>@lang('about.index.responsabilitiestasks')</td>
        <td colspan="5">@lang('about.index.dataentryapps')</td>
      </tr>

      <tr>
        <td>@lang('about.index.companydata')</td>
        <td colspan="5">@lang('about.index.companydescription.1')</td>
      </tr>

      <tr>
        <td colspan="6">
          <h2>@lang('about.index.languageknowledge')</h2>
        </td>
      </tr>

      <tr>
        <td>@lang('about.index.nativelanguage')</td>
        <td colspan="5">@lang('about.index.latvian')</td>
      </tr>

      <tr>
        <td></td>
        <th>@lang('about.index.dialog')</th>
        <th>@lang('about.index.monologue')</th>
        <th>@lang('about.index.reading')</th>
        <th>@lang('about.index.listening')</th>
        <th>@lang('about.index.writing')</th>
      </tr>

      <tr>
        <th>@lang('about.index.english')</th>
        <td>@lang('about.index.b1')</td>
        <td>@lang('about.index.b1')</td>
        <td>@lang('about.index.b2')</td>
        <td>@lang('about.index.b2')</td>
        <td>@lang('about.index.b1')</td>
      </tr>

      <tr>
        <th>@lang('about.index.russian')</th>
        <td>@lang('about.index.b2')</td>
        <td>@lang('about.index.b2')</td>
        <td>@lang('about.index.c1')</td>
        <td>@lang('about.index.c1')</td>
        <td>@lang('about.index.a2')</td>
      </tr>

      <tr>
        <td colspan="6">
          <h2>@lang('about.index.computerknowledge')</h2>
        </td>
      </tr>

      <tr>
        <td>@lang('about.index.basiclevel')</td>
        <td colspan="5">CSS/SASS (Zurb Foundation, Semantic UI), C#, AHK, ZPL2</td>
      </tr>

      <tr>
        <td>@lang('about.index.mediumlevel')</td>
        <td colspan="5">
          HTML<br>
          JavaScript (VueJs)<br>
          PHP (Laravel)<br>
          PostgreSQL<br>
          Git<br>
          Windows 7/10
        </td>
      </tr>

      <tr>
        <td>@lang('about.index.addinfocomputerskills')</td>
        <td colspan="5">
          @lang('about.index.basicinfo')
          <br>
          @lang('about.index.activeinfo')
          <br>
          @lang('about.index.passiveinfo')
          <br>
          @lang('about.index.workorganization')
        </td>
      </tr>
    </table>

    <footer>
      <a
        class="red"
        href="http://www.piemeram.lv"
      >
        www.piemeram.lv
      </a>
    </footer>
  </body>
</html>
