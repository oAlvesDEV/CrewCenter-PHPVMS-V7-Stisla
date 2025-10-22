{{-- resources/views/widgets/profile/last30.blade.php --}}
@php
use App\Models\Pirep;
use App\Models\Enums\PirepState;
use Illuminate\Support\Carbon;

/** @var \App\Models\User $user */
$from = Carbon::now()->subDays(30);
$to   = Carbon::now();

$base = Pirep::query()
  ->where('user_id', $user->id)
  ->where(function ($q) use ($from, $to) {
     $q->whereBetween('submitted_at', [$from, $to])
       ->orWhere(function ($q2) use ($from, $to) {
         $q2->whereNull('submitted_at')->whereBetween('created_at', [$from, $to]);
       });
  });

$pirepsFiled  = (clone $base)->count();
$accepted     = (clone $base)->where('state', PirepState::ACCEPTED);

$mins         = (int) $accepted->sum('flight_time');
$hoursHuman   = sprintf('%d:%02d', intdiv($mins,60), $mins%60);

$avgLanding   = (clone $accepted)->whereNotNull('landing_rate')->avg('landing_rate');
$avgLanding   = is_null($avgLanding) ? '—' : round($avgLanding).' fpm';

$fuelSetting  = strtolower((string) setting('units.fuel','lbs'));
$fuelRaw      = (int) (clone $accepted)->sum('fuel_used');
$fuelLbs      = $fuelSetting === 'kg' ? (int) round($fuelRaw * 2.2046226218) : $fuelRaw;
@endphp

<div class="card shadow card-statistics border-0">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>{{ __('Last 30 Days') }}</h6>
    <span class="badge bg-light text-primary">{{ $from->format('M j') }} -> {{ $to->format('M j') }}</span>
  </div>
  <div class="card-body pb-1">
    <div class="row text-center">
      
      <div class="col-6 col-md-3 mb-3">
        <div class="stat-box p-3 rounded shadow-sm bg-light">
          <div class="text-muted small">{{ trans_choice('common.pirep', 2) }} {{ __('Filed') }}</div>
          <div class="stat-value display-6 fw-bold text-primary">{{ number_format($pirepsFiled) }}</div>
          <i class="fas fa-file-alt text-primary fa-lg mt-1"></i>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-3">
        <div class="stat-box p-3 rounded shadow-sm bg-light">
          <div class="text-muted small">{{ __('Accepted Hours') }}</div>
          <div class="stat-value display-6 fw-bold text-success">{{ $hoursHuman }}</div>
          <i class="fas fa-clock text-success fa-lg mt-1"></i>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-3">
        <div class="stat-box p-3 rounded shadow-sm bg-light">
          <div class="text-muted small">{{ __('Avg Landing Rate') }}</div>
          <div class="stat-value display-6 fw-bold text-warning">{{ $avgLanding }}</div>
          <i class="fas fa-plane-arrival text-warning fa-lg mt-1"></i>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-3">
        <div class="stat-box p-3 rounded shadow-sm bg-light">
          <div class="text-muted small">{{ __('Fuel Used') }} (lbs)</div>
          <div class="stat-value display-6 fw-bold text-danger">{{ number_format($fuelLbs) }}</div>
          <i class="fas fa-gas-pump text-danger fa-lg mt-1"></i>
        </div>
      </div>
      
    </div>
  </div>
</div>

<style>
  .stat-box {
    transition: all 0.2s ease-in-out;
  }
  .stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
  }
  .stat-value {
    font-size: 1.5rem;
  }
</style>
