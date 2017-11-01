@extends('layout')
@section('content')
<h1>@lang('project.title')</h1>

<div class="ui card">
  <div class="content">
    <div class="header">
      {{ title_case(trans('project.torrentfinder.title')) }}
    </div>
    <div class="meta">1.0.0.26</div>
    <div class="description">
      Ar šo aplikāciju jūs varat meklēt torrentus vienlaicīgi trīs trakeros.
      Galvenā sadaļā ir izvēlne Šodienas, kur var meklēt šodien pievienotos torrentus.
      Filmas sadaļā ir iespēja atlasīt sev tīkamu žanru vai kādu konkrētu gadu. Spēles sadaļā varam izvēlēties sev vajadzīgo platformu.
      Kā arī pats meklētājs, kur varam meklēt, kas vien ienāk prātā un arī iespēja atlasīt konkrētu kategoriju.
      Interesentiem vajadzēs reģistrēties trakeru mājaslapās.
      Izstrādātājs ir sagatavojis lietotājus eksperimentālos nolūkos, lai būtu iespēja iepazīties ar aplikāciju pilnvērtīgi.
    </div>
  </div>
  <div class="extra content">
    <a
      class="ui button"
      href="{{ asset('piemeram.lv/TM2/TM2.application') }}"
    >
      @lang('system.download')
    </a>
  </div>
</div>
@endsection
