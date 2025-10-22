{{-- resources/views/widgets/profile/recent_pireps.blade.php --}}
@php
use App\Models\Pirep;
use App\Models\Enums\PirepState;

/** @var \App\Models\User $user */
$limit = $limit ?? 5;
$rows = Pirep::with(['dpt_airport','arr_airport','aircraft'])
  ->where('user_id', $user->id)
  ->orderByDesc('submitted_at')
  ->limit($limit)
  ->get();
@endphp

<div class="card shadow-lg border-0 rounded-3 glass-card">
  <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 px-4 pt-3 pb-0">
    <h6 class="card-title fw-bold mb-0 text-primary">
      <i class="fas fa-plane-departure me-2 text-info"></i>{{ __('Recent PIREPs') }}
    </h6>
    <span class="badge bg-light text-dark">{{ $rows->count() }}</span>
  </div>

  <div class="card-body p-0">
    @if($rows->isEmpty())
      <div class="text-center text-muted py-4">
        <i class="fas fa-inbox fa-lg mb-2 d-block"></i>
        <small>{{ __('No reports yet') }}</small>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-sm">
          <thead class="bg-light text-uppercase text-muted small">
            <tr>
              <th class="ps-4">{{ __('Flight') }}</th>
              <th>{{ __('Departure') }}</th>
              <th>{{ __('Arrival') }}</th>
              <th class="text-center">{{ __('Time') }}</th>
              <th class="text-center">{{ __('Status') }}</th>
              <th class="text-end pe-4"></th>
            </tr>
          </thead>
          <tbody>
          @foreach($rows as $p)
            @php
              $color = 'bg-info';
              if($p->state === PirepState::PENDING)   $color = 'bg-warning';
              elseif($p->state === PirepState::ACCEPTED) $color = 'bg-success';
              elseif($p->state === PirepState::REJECTED) $color = 'bg-danger';
            @endphp
            <tr class="border-bottom">
              <td class="ps-4 fw-semibold">
                <a href="{{ route('frontend.pireps.show', [$p->id]) }}" class="text-decoration-none text-primary">
                  {{ $p->ident }}
                </a>
              </td>
              <td>{{ optional($p->dpt_airport)->icao ?? $p->dpt_airport_id }}</td>
              <td>{{ optional($p->arr_airport)->icao ?? $p->arr_airport_id }}</td>
              <td class="text-center text-muted">@minutestotime($p->flight_time)</td>
              <td class="text-center">
                <span class="badge {{ $color }} text-white px-3 py-1 rounded-pill">
                  {{ \App\Models\Enums\PirepState::label($p->state) }}
                </span>
              </td>
              <td class="text-end pe-4">
                <a href="{{ route('frontend.pireps.show', [$p->id]) }}" 
                   class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                  <i class="fas fa-eye me-1"></i> {{ __('View') }}
                </a>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

{{-- Estilo opcional de vidro premium --}}
<style>
.glass-card {
  backdrop-filter: blur(12px);
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.table-hover tbody tr:hover {
  background-color: rgba(0, 123, 255, 0.05);
  transition: 0.2s;
}
.card-title {
  font-size: 1rem;
  letter-spacing: .5px;
}
.text-sm { font-size: .9rem; }
</style>
