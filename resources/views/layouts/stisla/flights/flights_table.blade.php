<div class="row">
  <div class="col">
    <div class="text-nowrap mb-2 d-flex justify-content-between flex-row overflow-auto">
      <div class="mx-2">@sortablelink('airline_id', __('common.airline'))</div>
      <div class="mx-2">@sortablelink('flight_number', __('flights.flightnumber'))</div>
      <div class="mx-2">@sortablelink('dpt_airport_id', __('airports.departure'))</div>
      <div class="mx-2">@sortablelink('arr_airport_id', __('airports.arrival'))</div>
      <div class="mx-2">@sortablelink('dpt_time', 'STD')</div>
      <div class="mx-2">@sortablelink('arr_time', 'STA')</div>
      <div class="mx-2">@sortablelink('distance', 'Distance')</div>
      <div class="mx-2">@sortablelink('flight_time', 'Flight Time')</div>
    </div>
  </div>
</div>
<div class="d-grid gap-2">
  @foreach($flights as $flight)
    <div class="col-md-12">
      <div class="card">
        <div class="position-relative hover-action">
          <div class="position-absolute h-100 w-100 z-0 d-flex">
            <div style="margin: auto 1rem; width: 100%; height: 100%; max-height: 150px; opacity: .25; top:0; bottom:0; left:1rem;">
              <div class="h-100" style="background: url('{{ch_getAirlineLogoWidget($flight->airline->icao, $flight->airline->logo)}}') no-repeat; background-size: contain"></div>
            </div>
          </div>
          <a class="text-decoration-none text-reset" href="#" data-bs-toggle="modal" data-bs-target="#details_{{$flight->id}}">
            <div class="card-body " style="position: relative; z-index: 2; min-height: 0">
              <div class="row">
                <div class="col-sm-12">
                  <div class="flex-row justify-content-between d-none d-lg-flex">
                    <div class="">{{ $flight->airline->name }}</div>
                    <div>{{$flight->flight_type}} ({{\App\Models\Enums\FlightType::label($flight->flight_type)}})</div>
                  </div>

                  <div style="font-size: 2rem; line-height: 2rem; font-weight: 600; text-align: center">
                    <span class="flight_title" x-id="{{ $flight->id }}">@if(isset($saved[$flight->id])) <i class="bi bi-bookmark-fill"></i> @endif</span>
                    @if($flight->airline->iata)
                      {{ $flight->airline->icao }}{{$flight->flight_number}} |
                    @endif
                    {{ $flight->ident }}
                    @if(filled($flight->callsign) && !setting('simbrief.callsign', true))
                      {{ '| '. $flight->atc }}
                    @endif

                  </div>


                  <div class="text-center my-2 d-flex flex-row justify-content-center">
                    <div>
                      <div style="font-size: 3rem; line-height: 3rem; font-weight: 600">
                        {{$flight->dpt_airport_id}}
                      </div>
                      <div style="font-size: 24px; line-height: 24px;">
                        {{$flight->dpt_time}}
                      </div>
                    </div>
                    <div class="mx-4 mt-0" style="font-size: 24px; line-height: 48px">to</div>
                    <div>
                      <div style="font-size: 3rem; line-height: 3rem; font-weight: 600">
                        {{$flight->arr_airport_id}}
                      </div>
                      <div style="font-size: 24px; line-height: 24px;">
                        {{$flight->arr_time}}
                      </div>
                    </div>
                  </div>
                  <div class="text-center fs-5">
                    @if($flight->flight_time)@minutestotime($flight->flight_time)@endif{{$flight->flight_time && $flight->distance ? '/' : ''}}{{$flight->distance ? $flight->distance.'nm' : ''}}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="d-flex flex-row justify-content-between">
                    <div class="d-flex flex-row">
                      <div><i class="bi-airplane-fill"></i>&nbsp;</div>
                      <div class="mb-2">
                        @if(count($flight->subfleets) !== 0)
                          @php
                            $arr = [];
                            foreach ($flight->subfleets as $sf) {
                                $tps = explode('-', $sf->type);
                                $type = last($tps);
                                $arr[] = "{$sf->type}";
                            }
                          @endphp
                          {{implode(", ", $arr)}}
                        @else
                          Any Subfleet
                        @endif
                      </div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </a>
          <div class="hover-container position-absolute w-100 bg-secondary bg-opacity-75 h-100 top-0 d-flex">
            <div class="m-auto">
              <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#details_{{$flight->id}}"><i class="bi bi-info-circle"></i></button>
              @if ($acars_plugin)
                @if (isset($saved[$flight->id]))
                  <a href="vmsacars:bid/{{ $saved[$flight->id] }}" class="btn btn-lg btn-primary"><i class="bi bi-download-filled"></i> vmsACARS</a>
                @else
                  <a href="vmsacars:flight/{{ $flight->id }}" class="btn btn-lg btn-primary"><i class="bi bi-download-filled"></i> vmsACARS</a>
                @endif
              @endif
              <!-- Simbrief enabled -->
              @if ($simbrief !== false)
                <!-- If this flight has a briefing, show the link to view it-->
                @if ($flight->simbrief && $flight->simbrief->user_id === $user->id)
                  <a href="{{ route('frontend.simbrief.briefing', $flight->simbrief->id) }}"
                     class="btn btn-lg btn-primary">
                    <i class="bi bi-eye"></i>&nbsp;OFP
                  </a>
                @else
                  <!-- Show button if the bids-only is disable, or if bids-only is enabled, they've saved it -->
                  @if ($simbrief_bids === false || ($simbrief_bids === true && isset($saved[$flight->id])))
                    @php
                      $aircraft_id = isset($saved[$flight->id]) ? App\Models\Bid::find($saved[$flight->id])->aircraft_id : null;
                    @endphp
                    <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}@if($aircraft_id)&aircraft_id={{ $aircraft_id }} @endif"
                       class="btn btn-lg btn-primary">
                      <i class="bi bi-plus-lg"></i>&nbsp;OFP
                    </a>
                  @endif
                @endif
              @endif
              <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}"
                 class="btn btn-lg btn-info">
                <i class="bi bi-journal-plus"></i>
              </a>
              @if (!setting('pilots.only_flights_from_current') || $flight->dpt_airport_id == $user->current_airport->icao)
                <button class="btn btn-lg save_flight
                           {{ isset($saved[$flight->id]) ? 'btn-warning':'btn-success' }}"
                        x-id="{{ $flight->id }}"
                        x-saved-class="btn-warning"
                        x-not-saved-class="btn-success"
                        type="button"
                        title="@lang('flights.addremovebid')">
                  <i class="bi {{isset($saved[$flight->id]) ? "bi-bookmark-x-fill" : "bi-bookmark-plus-fill"}}"></i>
                </button>
              @endif
            </div>

          </div>
        </div>


      </div>
    </div>
    <div class="modal fade" id="details_{{$flight->id}}" tabindex="-1" aria-labelledby="details-{{$flight->id}}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{$flight->ident}} Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h2>Airline</h2>
            <ul class="list-group">
              <li class="list-group-item">Name: {{$flight->airline->name}}</li>
              <li class="list-group-item">ICAO: {{$flight->airline->icao}}</li>
              <li class="list-group-item">IATA: {{$flight->airline->iata}}</li>
              <li class="list-group-item">ATC Callsign: {{$flight->airline->callsign}}</li>
            </ul>
            <h2>Flight Details</h2>
            <ul class="list-group">
              <li class="list-group-item">Flight Type: {{$flight->flight_type}}</li>
              <li class="list-group-item">Distance: {{$flight->distance}}</li>
              <li class="list-group-item">Estimated Time: @minutestotime($flight->flight_time)</li>
              <li class="list-group-item">Notes: {{$flight->notes}}</li>
            </ul>
          </div>
          <div class="mx-auto">
            @if ($acars_plugin)
              @if (isset($saved[$flight->id]))
                <a href="vmsacars:bid/{{ $saved[$flight->id] }}" class="btn btn-lg btn-primary"><i class="bi bi-download-filled"></i> vmsACARS</a>
              @else
                <a href="vmsacars:flight/{{ $flight->id }}" class="btn btn-lg btn-primary"><i class="bi bi-download-filled"></i> vmsACARS</a>
              @endif
            @endif
            <!-- Simbrief enabled -->
            @if ($simbrief !== false)
              <!-- If this flight has a briefing, show the link to view it-->
              @if ($flight->simbrief && $flight->simbrief->user_id === $user->id)
                <a href="{{ route('frontend.simbrief.briefing', $flight->simbrief->id) }}"
                   class="btn btn-lg btn-primary">
                  <i class="bi bi-eye"></i>&nbsp;OFP
                </a>
              @else
                <!-- Show button if the bids-only is disable, or if bids-only is enabled, they've saved it -->
                @if ($simbrief_bids === false || ($simbrief_bids === true && isset($saved[$flight->id])))
                  @php
                    $aircraft_id = isset($saved[$flight->id]) ? App\Models\Bid::find($saved[$flight->id])->aircraft_id : null;
                  @endphp
                  <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}@if($aircraft_id)&aircraft_id={{ $aircraft_id }} @endif"
                     class="btn btn-lg btn-primary">
                    <i class="bi bi-plus-lg"></i>&nbsp;OFP
                  </a>
                @endif
              @endif
            @endif
            <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}"
               class="btn btn-lg btn-info">
              <i class="bi bi-journal-plus"></i>
            </a>
            @if (!setting('pilots.only_flights_from_current') || $flight->dpt_airport_id == $user->current_airport->icao)
              <button class="btn btn-lg save_flight
                           {{ isset($saved[$flight->id]) ? 'btn-warning':'btn-success' }}"
                      x-id="{{ $flight->id }}"
                      x-saved-class="btn-warning"
                      x-not-saved-class="btn-success"
                      type="button"
                      title="@lang('flights.addremovebid')">
                <i class="bi {{isset($saved[$flight->id]) ? "bi-bookmark-x-fill" : "bi-bookmark-plus-fill"}}"></i>
              </button>
            @endif
          </div>
          <div class="modal-footer">
            <a class="btn btn-info" href="{{ route('frontend.flights.show', [$flight->id]) }}">Even More Info</a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  @endforeach
</div>

