<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead>
          <tr class="text-center">
            <th>@sortablelink('flight_number', trans_choice('common.flight', 1))</th>
            <th>@sortablelink('dpt_airport_id', __('common.departure'))</th>
            <th>@sortablelink('arr_airport_id', __('common.arrival'))</th>
            <th>@sortablelink('aircraft_id', __('common.aircraft'))</th>
            <th>@sortablelink('flight_time', __('flights.flighttime'))</th>
            <th>@sortablelink('status', __('common.status'))</th>
            <th>@sortablelink('submitted_at', __('pireps.submitted'))</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pireps as $pirep)
            <tr>
              <td>
                <a href="{{ route('frontend.pireps.show', [$pirep->id]) }}" class="font-weight-bold text-info">
                  {{ $pirep->ident }}
                </a>
              </td>
              <td>
                @if($pirep->dpt_airport){{ $pirep->dpt_airport->name }}@endif
                (<a href="{{ route('frontend.airports.show', [$pirep->dpt_airport_id]) }}">
                  {{ $pirep->dpt_airport_id }}
                </a>)
              </td>
              <td>
                @if($pirep->arr_airport){{ $pirep->arr_airport->name }}@endif
                (<a href="{{ route('frontend.airports.show', [$pirep->arr_airport_id]) }}">
                  {{ $pirep->arr_airport_id }}
                </a>)
              </td>
              <td>
                {{ optional($pirep->aircraft)->ident ?? '-' }}
              </td>
              <td class="text-center">
                @minutestotime($pirep->flight_time)
              </td>
              <td class="text-center">
                @php
                  $color = 'badge-info';
                  if($pirep->state === PirepState::PENDING) {
                    $color = 'badge-warning';
                  } elseif ($pirep->state === PirepState::ACCEPTED) {
                    $color = 'badge-success';
                  } elseif ($pirep->state === PirepState::REJECTED) {
                    $color = 'badge-danger';
                  }
                @endphp
                <span class="badge {{ $color }}">
                  {{ PirepState::label($pirep->state) }}
                </span>
              </td>
              <td>
                @if(filled($pirep->submitted_at))
                  {{ $pirep->submitted_at->diffForHumans() }}
                @endif
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
