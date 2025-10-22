<style>
.pirep-dashboard {
  max-width: 1000px;
  margin: 0 auto;
}

.pirep-row {
  background: #fff;
  border-left: 4px solid #0d6efd; /* destaque azul */
  transition: transform 0.2s;
}

.pirep-row:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.flight-ident {
  font-size: 1.2rem;
}

.route {
  font-size: 0.9rem;
}
</style>

<div class="pirep-dashboard">
  @foreach($pireps as $pirep)
    <div class="pirep-row shadow-sm rounded mb-3 p-3 d-flex align-items-center justify-content-between">
      
      <!-- Avatar e usuário -->
      <div class="d-flex align-items-center">
        <div class="avatar me-3">
          @if ($pirep->user->avatar)
            <img src="{{ $pirep->user->avatar->url }}" class="rounded-circle" style="height: 50px; width: 50px;" />
          @else
            <img src="{{ $pirep->user->gravatar(50) }}" class="rounded-circle" style="height: 50px; width: 50px;" />
          @endif
        </div>
        <div>
          <h6 class="mb-0">{{ $pirep->user->name_private }}</h6>
          <small class="text-muted">{{ $pirep->airline->name }}</small>
        </div>
      </div>

      <!-- Informações do voo -->
      <div class="d-flex flex-column text-center flex-grow-1">
        <span class="flight-ident fw-bold">{{ $pirep->ident }}</span>
        <span class="route text-muted">{{ $pirep->dpt_airport_id }} &#9992; {{ $pirep->arr_airport_id }}</span>
      </div>

      <!-- Tipo de voo e aeronave -->
      <div class="text-end">
        <span class="badge bg-info mb-1">{{ \App\Models\Enums\FlightType::label($pirep->flight_type) }}</span>
        <div class="text-muted">{{ optional($pirep->aircraft)->ident ?? '-' }}</div>
      </div>

    </div>
  @endforeach
</div>
