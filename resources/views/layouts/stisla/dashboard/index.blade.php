@extends('app')
@section('title', __('common.dashboard'))

@section('css')
<style>
  :root{
    --card-radius: 1rem;
    --glass-bg: rgba(255,255,255,.9);
    --glass-brd: rgba(13,110,253,.15);
  }

  /* —— HERO (kept) —— */
  .dash-hero{
    background:
      radial-gradient(1200px 400px at -10% -30%, #e3f2ff 0, transparent 60%),
      radial-gradient(900px 300px at 120% 10%, #ffe9f5 0, transparent 60%),
      linear-gradient(135deg,#f6fbff 0%,#eef5ff 50%,#f8fbff 100%);
    border: 1px solid rgba(0,0,0,.06);
    border-radius: var(--card-radius);
    box-shadow: 0 8px 26px rgba(0,0,0,.06);
  }
  .chip{
    display:inline-flex; align-items:center; gap:.5rem;
    background: var(--glass-bg); backdrop-filter: blur(6px);
    border:1px solid var(--glass-brd); border-radius:999px; padding:.45rem .85rem;
  }

  /* —— PROFILE-STYLE CARDS —— */
  .tile{
    border-radius: var(--card-radius);
    color:#0f1a2b; border:1px solid rgba(0,0,0,.05);
    box-shadow: 0 10px 28px rgba(0,0,0,.06);
    transition: transform .15s ease, box-shadow .15s ease;
  }
  .tile:hover{ transform: translateY(-2px); box-shadow:0 16px 36px rgba(0,0,0,.10); }
  .tile-bg-1{ background: linear-gradient(135deg,#e7f0ff,#e9fff7); }
  .tile-bg-2{ background: linear-gradient(135deg,#fff3e7,#e7f2ff); }
  .tile-bg-3{ background: linear-gradient(135deg,#f3e8ff,#e7f7ff); }
  .tile-bg-4{ background: linear-gradient(135deg,#fff0f5,#eaf7ff); }
  .stat-number{ font-weight: 800; letter-spacing:.2px; }

  .card-glass{
    background: var(--glass-bg); backdrop-filter: blur(6px);
    border:1px solid rgba(0,0,0,.06); border-radius: var(--card-radius);
    box-shadow: 0 10px 28px rgba(0,0,0,.07);
  }

  /* Progress (same as profile) */
  .progress{ height: 10px; background:#e9eef6; border-radius:10px; }
  .progress-bar{ border-radius:10px; background: linear-gradient(90deg,#0d6efd,#6f42c1,#20c997); }

  /* Accents */
  .badge-soft{ background:#eef4ff; color:#0b3aa6; border:1px solid #d8e5ff; }
  .leave-alert{ border:1px dashed #ffda6a; background:#fff9e6; color:#7a5a00; border-radius: var(--card-radius); padding:.85rem 1rem; }
</style>
@endsection

@section('content')
@php
  /**
   * Accepts App\Support\Money or scalar and returns a safe string.
   */
  if (!function_exists('format_money_any')) {
      function format_money_any($val) {
          if (is_object($val)) {
              if (method_exists($val, 'formatted')) return $val->formatted();
              if (method_exists($val, 'format'))    return $val->format();
              if (method_exists($val, '__toString')) return (string) $val;
              if (method_exists($val, 'toString'))  return $val->toString();
              if (method_exists($val, 'getAmount')) return number_format((float) $val->getAmount(), 2);
              if (property_exists($val, 'amount'))  return number_format((float) $val->amount, 2);
              return (string) json_encode($val);
          }
          return number_format((float) ($val ?? 0), 2);
      }
  }

  $balance = optional($user->journal)->balance;
  $totalFlights = (int) ($user->flights ?? 0);
  $currentAirportIcao = $current_airport ?? null;

  // Example placeholders; replace if you have exact values
  $current_rank = $user->rank->name ?? __('Pilot');
  $hrs_to_next = $hours_to_next ?? 15;
  $pct = isset($rank_progress_pct) ? max(0,min(100,$rank_progress_pct)) : 65;
@endphp

<div class="container-fluid px-0">

  {{-- HERO --}}
  <div class="dash-hero p-4 p-md-5 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="chip">👨‍✈️ {{ $user->name_private }}</span>
          <span class="chip">🛫 {{ $currentAirportIcao ?: '—' }}</span>
          <span class="chip">⏱️ @minutestotime($user->flight_time) {{ __('dashboard.totalhours') }}</span>
        </div>
        <h1 class="mb-1">{{ __('common.dashboard') }}</h1>
        <div class="text-muted">{{ __('Welcome back! Ready for your next leg?') }}</div>
      </div>
    </div>
  </div>

  @if (Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
    <div class="leave-alert mb-4 d-flex align-items-center gap-2">
      <span>⚠️</span>
      <div><strong>{{ __('You are on leave.') }}</strong> {{ __('File a PIREP to set your status to active!') }}</div>
    </div>
  @endif

  <div class="row g-4">
    {{-- LEFT --}}
    <div class="col-lg-8">

      {{-- STAT TILES — now using profile’s .tile styles --}}
      <div class="row g-4">
        <div class="col-6 col-md-3">
          <div class="tile tile-bg-1 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                  @include('widgets.airline.total_pilots')
              </div>
              <div class="fs-4">👥</div>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="tile tile-bg-2 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                 @include('widgets.airline.total_hours')
              </div>
              <div class="fs-4">⏱️</div>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="tile tile-bg-3 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                 @include('widgets.airline.total_pireps')
              </div>
              <div class="fs-4">📑</div>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="tile tile-bg-4 p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                  @include('widgets.airline.avg_landing_rate')
              </div>
              <div class="fs-4">🛬</div>
            </div>
          </div>
        </div>
      </div>
     <br>

            <div class="card">
    			<div class="card-header">
    				<h4>@lang('common.livemap')</h4>
    			</div>
				<div class="card-body p-0">
                    {{ Widget::DashMap() }}
				</div>
    		</div>


        {{-- Recent PIREPs --}}
        <div class="col-12">
            <div class="card">
    			<div class="card-header">
              <div class="fw-semibold text-uppercase small text-muted">🧾 {{ __('dashboard.recentreports') }}</div>
            </div>
            <div class="p-3">
              {{ Widget::latestPireps(['count' => 5]) }}
            </div>
          </div>
        </div>

      {{-- News --}}
      <div class="card-glass p-0 mt-4">
        <div class="p-3 pb-2 d-flex justify-content-between align-items-center">
          <div class="fw-semibold text-uppercase small text-muted">📰 {{ __('Latest News') }}</div>
        </div>
        <div class="p-3">
          {{ Widget::latestNews(['count' => 1]) }}
         
        </div>
      </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">
      <div class="row g-4 sidebar-sticky">

        {{-- Weather --}}
        <div class="col-12">
          <div class="card-glass p-0">
            <div class="p-3 pb-2 d-flex justify-content-between align-items-center">
              <div class="fw-semibold text-uppercase small text-muted">
                {{ __('dashboard.weatherat', ['ICAO' => $currentAirportIcao]) }}
              </div>
            </div>
            <div class="p-3 d-flex flex-column gap-3">
              {{ Widget::Weather(['icao' => $currentAirportIcao]) }}
            </div>
          </div>
        </div>

        {{-- Newest Pilots --}}
        <div class="col-12">
          <div class="card-glass p-0">
            <div class="p-3 pb-2 d-flex justify-content-between align-items-center">
              <div class="fw-semibold text-uppercase small text-muted">👨‍✈️ {{ __('common.newestpilots') }}</div>
            </div>
            <div class="p-3">
              {{ Widget::latestPilots(['count' => 5]) }}
            </div>
          </div>
        </div>


  </div>
</div>
@endsection
