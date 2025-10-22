<div class="row">
  @foreach($flights as $flight)
    <div class="col-md-12 mb-3">
      <div class="card card-primary shadow-sm flight-card">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
          <!-- Logo e Ident -->
          <div class="d-flex align-items-center mb-2 mb-md-0" style="min-width: 200px;">
            <img src="{{ $flight->airline->logo }}" alt="{{ $flight->airline->name }}" class="rounded mr-3" style="width: 50px; height: auto;">
            <div>
              <h5 class="mb-0">{{ $flight->ident }}</h5>
              @if(filled($flight->callsign) && !setting('simbrief.callsign', true))
                <small class="text-muted">{{ '| ' . $flight->atc }}</small>
              @endif
            </div>
          </div>

          <!-- Rotas -->
          <div class="d-flex flex-column text-center mb-2 mb-md-0">
            <span class="badge badge-info mb-1"><i class="fas fa-plane-departure"></i> {{ __('flights.dep') }}</span>
            <span>{{ optional($flight->dpt_airport)->name ?? $flight->dpt_airport_id }}</span>
            <small class="text-muted">({{ $flight->dpt_airport_id }})</small>
            @if($flight->dpt_time)<div class="text-muted">{{ $flight->dpt_time }}</div>@endif
          </div>

          <div class="d-flex flex-column text-center mb-2 mb-md-0">
            <span class="badge badge-success mb-1"><i class="fas fa-plane-arrival"></i> {{ __('flights.arr') }}</span>
            <span>{{ optional($flight->arr_airport)->name ?? $flight->arr_airport_id }}</span>
            <small class="text-muted">({{ $flight->arr_airport_id }})</small>
            @if($flight->arr_time)<div class="text-muted">{{ $flight->arr_time }}</div>@endif
          </div>

          <!-- Distância e tempo -->
          <div class="d-flex flex-column text-center mb-2 mb-md-0">
            <span class="badge badge-light mb-1"><i class="fas fa-ruler-combined"></i> {{ $flight->distance }} NM</span>
            <span class="badge badge-light"><i class="fas fa-clock"></i> {{ $flight->flight_time }}</span>
          </div>

          <!-- Ações -->
          <div class="d-flex flex-column align-items-end">
            <div class="mb-2">
              <a class="btn btn-sm btn-outline-primary mb-1" href="{{ route('frontend.flights.show', [$flight->id]) }}"><i class="fas fa-info-circle"></i> More Info</a>
              @if ($acars_plugin)
                <a href="vmsacars:{{ isset($saved[$flight->id]) ? 'bid/' . $saved[$flight->id] : 'flight/' . $flight->id }}" class="btn btn-sm btn-outline-primary mb-1"><i class="fas fa-download"></i> Load in vmsACARS</a>
              @endif
            </div>

            <div>
              @if ($simbrief !== false)
                @if ($flight->simbrief && $flight->simbrief->user_id === $user->id)
                  <a href="{{ route('frontend.simbrief.briefing', $flight->simbrief->id) }}" class="btn btn-sm btn-outline-primary mb-1"><i class="fas fa-file-alt"></i> View Simbrief</a>
                @elseif ($simbrief_bids === false || ($simbrief_bids === true && isset($saved[$flight->id])))
                  @php
                    $aircraft_id = isset($saved[$flight->id]) ? App\Models\Bid::find($saved[$flight->id])->aircraft_id : null;
                  @endphp
                  <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}@if($aircraft_id)&aircraft_id={{ $aircraft_id }} @endif" class="btn btn-sm btn-outline-primary mb-1"><i class="fas fa-file-alt"></i> Create Simbrief</a>
                @endif
              @endif
              <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}" class="btn btn-sm btn-outline-info mb-1"><i class="fas fa-plus-circle"></i> {{ __('pireps.newpirep') }}</a>
              @if (!setting('pilots.only_flights_from_current') || $flight->dpt_airport_id == $user->current_airport->icao)
                <button class="btn btn-sm save_flight {{ isset($saved[$flight->id]) ? 'btn-success' : 'btn-outline-success' }}"
                        x-id="{{ $flight->id }}"
                        x-saved-class="btn-success"
                        x-not-saved-class="btn-outline-success"
                        type="button" title="@lang('flights.addremovebid')">
                  {{ isset($saved[$flight->id]) ? "Remove Bid" : "Add Bid" }}
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<style>
.flight-card {
  border-top: 4px solid #6777ef;
  transition: transform 0.2s, box-shadow 0.2s;
}
.flight-card:hover {
  transform: scale(1.02);
  box-shadow: 0 8px 20px rgba(103, 119, 239, 0.3);
}
.badge-light {
  background-color: #f1f3f5;
  color: #495057;
}
</style>
