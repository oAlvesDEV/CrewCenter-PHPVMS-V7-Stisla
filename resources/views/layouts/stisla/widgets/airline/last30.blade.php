{{-- resources/views/widgets/airline/last30.blade.php --}}
@php
use App\Models\Pirep;
use App\Models\Enums\PirepState;
use Illuminate\Support\Carbon;

/**
 * Usage:
 *   @include('widgets.airline.last30')                       // global last 30d
 *   @include('widgets.airline.last30', ['airlineId' => 1])   // scoped to airline ID 1
 *
 * What this shows (last 30 days window):
 * - PIREPs Filed: count of submitted PIREPs (any state)
 * - Flight Hours: sum of flight_time for ACCEPTED PIREPs (HH:MM)
 * - Fuel Used (lbs): sum of fuel_used for ACCEPTED PIREPs, converted to lbs if needed
 * - Avg Landing Rate: average landing_rate (fpm) for ACCEPTED PIREPs
 */

$airlineId = $airlineId ?? null;
$to   = Carbon::now();
$from = Carbon::now()->subDays(30);

// Helper: base time window query; prefer submitted_at, fall back to created_at if null
$base = Pirep::query()
  ->when($airlineId, fn($q) => $q->where('airline_id', $airlineId))
  ->where(function ($q) use ($from, $to) {
      $q->whereBetween('submitted_at', [$from, $to])
        ->orWhere(function ($q2) use ($from, $to) {
          $q2->whereNull('submitted_at')
             ->whereBetween('created_at', [$from, $to]);
        });
  });

// PIREPs filed (all states)
$pirepsFiled30 = (clone $base)->count();

// ACCEPTED subset for quality KPIs
$accepted = (clone $base)->where('state', PirepState::ACCEPTED);

// Flight time (minutes) -> HH:MM
$totalMinutes = (int) $accepted->sum('flight_time');
$hours = intdiv($totalMinutes, 60);
$mins  = $totalMinutes % 60;
$hoursHuman = sprintf('%d:%02d', $hours, $mins);

// Fuel used -> lbs
// phpVMS unit setting often is 'kg' or 'lbs'
$fuelSetting = strtolower((string) setting('units.fuel', 'lbs'));
$fuelSumRaw  = (int) (clone $accepted)->sum('fuel_used'); // stored in configured unit
$fuelLbs = $fuelSetting === 'kg' ? (int) round($fuelSumRaw * 2.2046226218) : $fuelSumRaw;

// Average landing rate (fpm) — typically negative values; we show rounded mean
$avgLandingRate = (clone $accepted)->whereNotNull('landing_rate')->avg('landing_rate');
$avgLandingRate = is_null($avgLandingRate) ? null : round($avgLandingRate);

// Scope label chip
$scopeLabel = $airlineId ? 'Airline #'.$airlineId : __('All Airlines');
@endphp

<div class="card-glass">
  <div class="header-bar d-flex align-items-center justify-content-between">
    <span>📆 Last 30 Days</span>
    <div class="d-flex align-items-center">
      <span class="chip mr-2">{{ $scopeLabel }}</span>
      <span class="chip">{{ $from->format('M j') }} – {{ $to->format('M j') }}</span>
    </div>
  </div>

  <div class="p-3">
    <div class="row g-3">
      {{-- PIREPs Filed --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-1 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-file-alt mr-2"></i> {{ trans_choice('common.pirep', 2) }} {{ __('Filed') }}
          </div>
          <div class="stat-number fs-3">{{ number_format($pirepsFiled30) }}</div>
        </div>
      </div>

      {{-- Flight Hours (Accepted) --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-2 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="far fa-clock mr-2"></i> {{ __('Flight Hours') }}
          </div>
          <div class="stat-number fs-3">{{ $hoursHuman }}</div>
        </div>
      </div>

      {{-- Fuel Used (lbs) --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-3 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-gas-pump mr-2"></i> {{ __('Fuel Used') }} (lbs)
          </div>
          <div class="stat-number fs-3">{{ number_format($fuelLbs) }}</div>
        </div>
      </div>

      {{-- Avg Landing Rate --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-4 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Avg Landing Rate') }}
          </div>
          <div class="stat-number fs-3">
            {{ is_null($avgLandingRate) ? '—' : $avgLandingRate.' fpm' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
