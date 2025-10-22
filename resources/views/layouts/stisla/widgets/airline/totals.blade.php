{{-- resources/views/widgets/airline/totals.blade.php --}}

@php
use App\Models\User;
use App\Models\Pirep;
use App\Models\Flight;
use App\Models\Enums\PirepState;

/**
 * Usage:
 *   @include('widgets.airline.totals')                // global totals
 *   @include('widgets.airline.totals', ['airlineId' => 1]) // scoped to airline ID = 1
 *
 * Notes:
 * - "PIREP Filed" = all PIREPs (any state)
 * - "Flight Hours" sums ACCEPTED PIREPs (typical KPI practice). Change if you want all.
 */

// Allow caller to pass $airlineId or infer from logged-in user; fallback to global
$airlineId = $airlineId
    ?? optional(Auth::user()->airline)->id
    ?? null;

// Base queries (conditionally scoped)
$pilotCount = User::query()
    ->when($airlineId, fn($q) => $q->where('airline_id', $airlineId))
    ->count();

$pirepsAll = Pirep::query()
    ->when($airlineId, fn($q) => $q->where('airline_id', $airlineId));

$pirepsFiled = (clone $pirepsAll)->count();

// Flight hours from ACCEPTED PIREPs (change to ->sum('flight_time') on $pirepsAll if you want ALL)
$totalMinutes = (clone $pirepsAll)
    ->where('state', PirepState::ACCEPTED)
    ->sum('flight_time');

$hours = intdiv((int)$totalMinutes, 60);
$mins  = (int)$totalMinutes % 60;
$hoursHuman = sprintf('%d:%02d', $hours, $mins);

// Flights count (if your Flight model/table exists and has airline_id)
$flightsCount = class_exists(Flight::class)
    ? Flight::query()
        ->when($airlineId, fn($q) => $q->where('airline_id', $airlineId))
        ->count()
    : 0;

// Small badge to show scope
$scopeLabel = $airlineId ? 'Airline #' . $airlineId : __('All Airlines');
@endphp

<div class="card-glass">
  <div class="header-bar d-flex align-items-center justify-content-between">
    <span>✈️ Airline Statistics</span>
    
  </div>

  <div class="p-3">
    <div class="row g-3">
      {{-- Pilots --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-1 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-user-friends mr-2"></i> {{ __('Pilots') }}
          </div>
          <div class="stat-number fs-3">{{ number_format($pilotCount) }}</div>
        </div>
      </div>

      {{-- Flights (Schedules) --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-2 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="fab fa-avianex mr-2"></i> {{ trans_choice('common.flight', 2) }}
          </div>
          <div class="stat-number fs-3">{{ number_format($flightsCount) }}</div>
        </div>
      </div>

      {{-- PIREPs Filed (All states) --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-3 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="fas fa-file-alt mr-2"></i> {{ trans_choice('common.pirep', 2) }} {{ __('Filed') }}
          </div>
          <div class="stat-number fs-3">{{ number_format($pirepsFiled) }}</div>
        </div>
      </div>

      {{-- Total Flight Hours (Accepted) --}}
      <div class="col-6 col-md-3 mb-3">
        <div class="tile tile-bg-4 p-3 h-100 d-flex flex-column justify-content-between">
          <div class="text-muted small d-flex align-items-center">
            <i class="far fa-clock mr-2"></i> {{ __('Flight Hours') }}
          </div>
          <div class="stat-number fs-3">{{ $hoursHuman }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
