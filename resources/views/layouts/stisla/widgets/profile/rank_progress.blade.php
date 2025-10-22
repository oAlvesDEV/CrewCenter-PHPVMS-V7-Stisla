@php
/**
 * Widget: Rank Progress
 * Location: resources/views/widgets/profile/rank_progress.blade.php
 *
 * Props:
 * - $user   : \App\Models\User   (required)
 * - $title  : string             (optional, default: __('Rank Progress'))
 * - $class  : string             (optional, default: 'col-12 col-md-6 col-xl-3')
 *
 * Notes:
 * - Uses $user->flight_time (minutes) to compute current hours
 * - Uses \App\Models\Rank (hours column = required hours in HOURS)
 * - If user is at top rank, shows “Top rank achieved” state
 */

use App\Models\Rank;

$title = $title ?? __('Rank Progress');
$class = $class ?? 'col-12 col-md-6 col-xl-3';

$currentMinutes = (int) ($user->flight_time ?? 0);
$currentHours   = round($currentMinutes / 60, 2);

// Pull all ranks in ascending order of required hours
$ranks = Rank::orderBy('hours', 'asc')->get();

// Identify current & next ranks
$currentRank    = $user->rank ?? $ranks->firstWhere(fn($r) => $r->hours <= $currentHours);
$nextRank       = $ranks->firstWhere(fn($r) => $r->hours > $currentHours);

// Compute progress to next rank
$targetHours    = $nextRank->hours ?? $currentRank->hours ?? 0;
$fromHours      = $currentRank ? $currentRank->hours : 0;

// Ensure sane bounds
$range          = max(0.0001, $targetHours - $fromHours); // prevent /0
$clampedNow     = min(max($currentHours, $fromHours), $targetHours);
$progressPct    = $nextRank ? round((($clampedNow - $fromHours) / $range) * 100, 1) : 100;

$remainingHours = $nextRank ? max(0, round($targetHours - $currentHours, 2)) : 0;

// Friendly headline text
if ($nextRank) {
    // e.g., "Captain in 23 hrs"
    $etaText = $remainingHours >= 1
        ? __(':rank in :hrs hrs', ['rank' => $nextRank->name, 'hrs' => number_format($remainingHours, 0)])
        : __(':rank almost there!', ['rank' => $nextRank->name]);

    $subText = __('Next at :hours hrs', ['hours' => (int) $nextRank->hours]);
} else {
    $etaText = __('Top rank achieved');
    $subText = $currentRank ? $currentRank->name : '';
}
@endphp

<div class="card-glass mb-3">
  <div class="card card-glass shadow-sm rounded-3 h-100">
    <div class="header-bar d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <span class="chip"><i class="fas fa-trophy"></i>&nbsp;{{ $title }}</span>
      </div>
      @if($currentRank)
        <small class="text-muted">{{ $currentRank->name }}</small>
      @endif
    </div>

    <div class="card-body p-3">
      {{-- Headline / ETA --}}
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="font-weight-bold">
          {{ $etaText }}
        </div>
        <div class="text-muted small">
          {{ number_format($currentHours, 1) }}h
        </div>
      </div>

      {{-- Progress bar --}}
      <div class="progress mb-2" style="height: 10px;">
        <div
          class="progress-bar"
          role="progressbar"
          style="width: {{ $progressPct }}%;"
          aria-valuenow="{{ $progressPct }}"
          aria-valuemin="0"
          aria-valuemax="100">
        </div>
      </div>

      {{-- Scale markers --}}
      <div class="d-flex justify-content-between text-muted small">
        <span>
          {{ $fromHours > 0 ? number_format($fromHours, 0).'h' : '0h' }}
          @if($currentRank && $currentRank->name)
            · {{ $currentRank->name }}
          @endif
        </span>
        <span>
          @if($nextRank)
            {{ $subText }}
          @else
            {{ __('Max') }}
          @endif
        </span>
      </div>

      {{-- Mini facts row --}}
      <div class="row mt-3 text-center">
        <div class="col-4">
          <div class="stat-number mb-0">{{ number_format($currentHours, 1) }}</div>
          <small class="text-muted">{{ __('Hours') }}</small>
        </div>
        <div class="col-4">
          <div class="stat-number mb-0">
            @if($nextRank)
              {{ number_format(max(0, $remainingHours), 0) }}
            @else
              —
            @endif
          </div>
          <small class="text-muted">{{ __('To Next') }}</small>
        </div>
        <div class="col-4">
          <div class="stat-number mb-0">{{ rtrim(rtrim(number_format($progressPct, 1), '0'), '.') }}%</div>
          <small class="text-muted">{{ __('Complete') }}</small>
        </div>
      </div>
    </div>
  </div>
</div>
