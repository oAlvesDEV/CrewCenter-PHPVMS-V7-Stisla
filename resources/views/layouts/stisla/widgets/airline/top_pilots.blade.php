{{-- widgets/top_pilots_avg_score.blade.php --}}
@php
  // Period selector: 7d / 30d / 90d / all
  $period = request('period', '30d');

  $from = match ($period) {
      '7d'   => now()->subDays(7),
      '30d'  => now()->subDays(30),
      '90d'  => now()->subDays(90),
      'all'  => now()->startOfDay()->subYears(100),
      default => now()->subDays(30),
  };

  // Aggregate by user:
  //  - SUM flight_time (minutes)
  //  - SUM distance (nm)
  //  - AVG landing_rate
  //  - AVG score (leaderboard metric)
  $rows = \App\Models\Pirep::query()
      ->where('created_at', '>=', $from)
      ->select([
          'user_id',
          \Illuminate\Support\Facades\DB::raw('COALESCE(SUM(flight_time), 0) as total_minutes'),
          \Illuminate\Support\Facades\DB::raw('COALESCE(SUM(distance), 0) as total_distance_nm'),
          \Illuminate\Support\Facades\DB::raw('AVG(landing_rate) as avg_landing_fpm'),
          \Illuminate\Support\Facades\DB::raw('COUNT(*) as flights'),
          \Illuminate\Support\Facades\DB::raw('COALESCE(AVG(score), 0) as avg_pirep_score'),
      ])
      ->groupBy('user_id')
      ->orderByDesc('avg_pirep_score')
      ->limit(200)
      ->get()
      ->keyBy('user_id');

  $users = \App\Models\User::query()
      ->whereIn('id', $rows->keys())
      ->select('id', 'name', 'pilot_id')
      ->get()
      ->keyBy('id');

  $leaders = $rows->map(function($r, $uid) use ($users) {
      $hours = round(($r->total_minutes ?? 0) / 60, 1);
      $dist  = (int) round($r->total_distance_nm ?? 0);
      $flts  = (int) ($r->flights ?? 0);
      $avgLd = $r->avg_landing_fpm !== null ? round($r->avg_landing_fpm) : null;

      return (object)[
          'user'            => $users->get($uid),
          'hours'           => $hours,
          'distance_nm'     => $dist,
          'flights'         => $flts,
          'avg_landing_fpm' => $avgLd,
          'avg_score'       => (float) $r->avg_pirep_score, // leaderboard metric (average)
      ];
  })
  ->sortByDesc('avg_score')
  ->take(10)
  ->values();
@endphp

<div class="card card-glass shadow-sm border-0 h-100">
  <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
    <h5 class="mb-0">🏅 Top Pilots <span class="text-muted">— Avg Score</span></h5>
    <div class="btn-group btn-group-sm">
      @foreach(['7d'=>'7d','30d'=>'30d','90d'=>'90d','all'=>'All'] as $k => $lbl)
        <a href="{{ request()->fullUrlWithQuery(['period' => $k]) }}"
           class="btn {{ $period === $k ? 'btn-success' : 'btn-outline-success' }}">
          {{ $lbl }}
        </a>
      @endforeach
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-sm mb-0 align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Pilot</th>
            <th class="text-end">Hours (Σ)</th>
            <th class="text-end">Distance (Σ)</th>
            <th class="text-end">Flights</th>
            <th class="text-end">Avg Ldg</th>
            <th class="text-end">Avg Score</th>
          </tr>
        </thead>
        <tbody>
        @forelse($leaders as $i => $row)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>
              @if($row->user)
                <span class="badge bg-primary me-2">{{ $row->user->pilot_id }}</span>
                {{ $row->user->name }}
              @else
                <span class="text-muted">Unknown</span>
              @endif
            </td>
            <td class="text-end">{{ number_format($row->hours, 1) }}</td>
            <td class="text-end">{{ number_format($row->distance_nm) }}</td>
            <td class="text-end">{{ number_format($row->flights) }}</td>
            <td class="text-end">{{ $row->avg_landing_fpm ?? '—' }}</td>
            <td class="text-end fw-bold">{{ number_format($row->avg_score, 0) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No data available.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
