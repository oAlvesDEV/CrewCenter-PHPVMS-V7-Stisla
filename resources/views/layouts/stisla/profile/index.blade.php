@extends('app')
@section('title', __('common.profile'))

@includeIf('theme_helpers')

@php
  $DBasic   = $DBasic   ?? (function_exists('check_module') ? check_module('DisposableBasic')  : false);
  $DSpecial = $DSpecial ?? (function_exists('check_module') ? check_module('DisposableSpecial') : false);
  $ivao_id  = optional($user->fields->firstWhere('name', Theme::getSetting('gen_ivao_field')))->value;
  $vatsim_id= optional($user->fields->firstWhere('name', Theme::getSetting('gen_vatsim_field')))->value;

  $units = $units ?? (
    function_exists('DT_GetUnits')
      ? DT_GetUnits()
      : ['currency' => 'USD', 'fuel' => 'kg', 'distance' => 'nm', 'weight' => 'kg']
  );

  $hasRoute = fn ($name) => app('router')->has($name);

  if (!function_exists('money_amount')) {
    function money_amount($val): float {
      if (is_null($val)) return 0.0;
      if (is_object($val)) {
        if (method_exists($val, 'toFloat')) return (float) $val->toFloat();
        if (method_exists($val, 'getAmount')) {
          $amt = $val->getAmount();
          if (is_object($amt) && method_exists($amt, 'toFloat')) return (float) $amt->toFloat();
          return is_numeric($amt) ? (float) $amt : 0.0;
        }
        if (property_exists($val, 'amount') && is_numeric($val->amount)) return (float) $val->amount;
        if (method_exists($val, '__toString')) {
          $str = (string) $val;
          if (preg_match('/-?\d+(?:[.,]\d+)?/', $str, $m)) return (float) str_replace(',', '', $m[0]);
        }
        return 0.0;
      }
      return (float) $val;
    }
  }

  if (!function_exists('format_money_any')) {
    function format_money_any($val): string {
      if (is_object($val)) {
        if (method_exists($val, 'formatted'))  return $val->formatted();
        if (method_exists($val, 'format'))     return $val->format();
        if (method_exists($val, '__toString')) return (string) $val;
      }
      return number_format(money_amount($val), 2);
    }
  }

  $user         = $user ?? Auth::user();
  $countryCode  = $user->country;
  $rank         = optional($user->rank);
  $airline      = optional($user->airline);
  $homeIcao     = optional($user->home_airport)->icao;
  $currIcao     = optional($user->current_airport)->icao;
  $balance      = optional($user->journal)->balance;
@endphp

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ __('common.profile') }}</h1>
  </div>

  <div class="section-body">

    {{-- HERO MODERNO --}}
    <div class="card shadow mb-4" style="border-radius:12px; overflow:hidden;">
      <div class="profile-widget-header text-center p-4" style="background: linear-gradient(135deg, #6f86d6, #48c6ef);">
        <img alt="image" src="{{ $user->avatar ? $user->avatar->url : $user->gravatar(512) }}" 
             class="rounded-circle border border-white shadow-lg" width="96" height="96">
        <h4 class="text-white mt-2">
          {{ $user->name_private }}
          @if(filled($user->country))
            <img src="https://flagcdn.com/24x18/{{ strtolower($user->country) }}.png" alt="flag">
          @endif
        </h4>
        <p class="text-white-50">{{ $user->callsign ?? '' }} 🆔 {{ $user->ident }}</p>

        <div class="mt-3">
          @if($ivao_id)
            <a href="https://www.ivao.aero/member.aspx?id={{ $ivao_id }}" target="_blank" class="badge bg-primary text-white px-3 py-2 shadow-sm me-1">🌐 IVAO {{ $ivao_id }}</a>
          @endif
          @if($vatsim_id)
            <a href="https://stats.vatsim.net/search_id.php?id={{ $vatsim_id }}" target="_blank" class="badge bg-info text-white px-3 py-2 shadow-sm me-1">✈️ VATSIM {{ $vatsim_id }}</a>
          @endif
          <span class="badge bg-success text-white px-3 py-2 shadow-sm">💰 {{ format_money_any($balance) ?? '0.00' }}</span>
        </div>
      </div>
      @includeIf('widgets.profile.rank_progress', ['user' => $user, 'class' => 'col-12 col-md-6 col-xl-3'])

      @if(Auth::check() && $user->id === Auth::user()->id)
        <div class="card-footer text-center">
          @if($hasRoute('frontend.profile.edit'))
            <a href="{{ route('frontend.profile.edit', [$user->id]) }}" class="btn btn-primary btn-sm">@lang('common.edit')</a>
          @endif
          @if(config('services.discord.enabled'))
            @if(!$user->discord_id)
              <a href="{{ route('oauth.redirect', ['provider' => 'discord']) }}" class="btn btn-outline-info btn-sm">Link Discord</a>
            @else
              <a href="{{ route('oauth.logout', ['provider' => 'discord']) }}" class="btn btn-outline-danger btn-sm">Unlink Discord</a>
            @endif
          @endif
        </div>
      @endif
    </div>

    <div class="row">
      {{-- LEFT --}}
      <div class="col-lg-8">
        {{-- Stats --}}
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 hover-scale">
              <div class="card-body d-flex align-items-center">
                <div class="icon bg-primary text-white rounded-circle p-3 me-3"><i class="fas fa-plane fa-lg"></i></div>
                <div>
                  <h5 class="mb-1">{{ __('Flights') }}</h5>
                  <h3 class="mb-0">{{ number_format($user->flights ?? 0) }}</h3>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 hover-scale">
              <div class="card-body d-flex align-items-center">
                <div class="icon bg-info text-white rounded-circle p-3 me-3"><i class="fas fa-clock fa-lg"></i></div>
                <div>
                  <h5 class="mb-1">@lang('flights.flighthours')</h5>
                  <h3 class="mb-0">@minutestotime($user->flight_time)</h3>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Average Score / Last PIREPs --}}
        @includeIf('widgets.profile.avg_score', ['user' => $user, 'title' => __('Average Scores Over Time')])
        @includeIf('widgets.profile.last30', ['user' => $user])
        <br>
        @includeIf('widgets.profile.recent_pireps', ['user' => $user, 'limit' => 7])
        <br>

        {{-- Bio --}}
        <div class="card mt-4 shadow-sm rounded-3">
          <div class="card-header bg-gradient-primary text-black">📝 {{ __('About the Pilot') }}</div>
          <div class="card-body">
            <p class="text-muted">{{ $user->bio ?? __('Professional virtual pilot profile. Keep flying sharp, safe, and smooth!') }}</p>
          </div>
        </div>
      </div>

      {{-- RIGHT --}}
      <div class="col-lg-4">
        {{-- Pilot Summary --}}
        <div class="card shadow-sm mt-4 rounded-3">
          <div class="card-header bg-gradient-primary text-black"><i class="fas fa-user"></i> {{ __('Pilot Summary') }}</div>
          <div class="card-body p-3">
            <table class="table table-borderless mb-0">
              <tr><td>Wallet</td><td>{{ format_money_any($balance) }}</td></tr>
              <tr><td>Rank</td><td>{{ $rank->name ?? '-' }}</td></tr>
              <tr><td>Home</td><td>{{ $homeIcao ?? '-' }}</td></tr>
              <tr><td>Current</td><td>{{ $currIcao ?? '-' }}</td></tr>
              <tr><td>Timezone</td><td>{{ $user->timezone }}</td></tr>
              @if(Auth::check() && $user->id === Auth::user()->id)
              @if(filled($user->api_key))
                <tr>
                  <td>🔑 API Key</td>
                  <td>
                    <span id="apiKey_show" style="display:none;">
                      {{ $user->api_key }}
                      <i class="bi bi-eye-slash ms-2" onclick="apiKeyHide()" title="@lang('common.hide')"></i>
                    </span>
                    <span id="apiKey_hide">
                      @lang('profile.apikey-show')
                      <i class="bi bi-eye ms-2" onclick="apiKeyShow()" title="@lang('common.show')"></i>
                    </span>
                  </td>
                </tr>
              @endif
               @endif
            </table>
          </div>
        </div>

        {{-- Awards --}}
        @if($user->awards && $user->awards->count())
          <div class="card mt-4 shadow-sm rounded-3">
            <div class="card-header bg-gradient-primary text-black">🏆 @lang('profile.your-awards')</div>
            <div class="card-body">
              <div class="row g-3">
                @foreach($user->awards as $award)
                  <div class="col-6 col-md-4 text-center">
                    <div class="award-card p-2 shadow-sm rounded-2" title="{{ $award->name }}">
                      <img src="{{ $award->image_url }}" alt="{{ $award->name }}" class="img-fluid mb-2" style="max-height:80px;">
                      <div class="small fw-bold">{{ $award->name }}</div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

<style>
.hover-scale:hover { transform: scale(1.03); transition: all 0.3s ease; }
.award-card:hover { transform: translateY(-5px); transition: all 0.2s ease-in-out; }
</style>

<script>
function apiKeyShow() {
  document.getElementById('apiKey_show').style.display = 'inline';
  document.getElementById('apiKey_hide').style.display = 'none';
}
function apiKeyHide() {
  document.getElementById('apiKey_show').style.display = 'none';
  document.getElementById('apiKey_hide').style.display = 'inline';
}
</script>
@endsection
