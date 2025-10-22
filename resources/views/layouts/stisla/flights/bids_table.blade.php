<div class="row">
  <div class="col">
    <table class="table table-sm table-borderless align-middle text-nowrap mb-2">
      <tr>
        <th>@sortablelink('airline_id', __('common.airline'))</th>
        <th>@sortablelink('flight_number', __('flights.flightnumber'))</th>
        <th>@sortablelink('dpt_airport_id', __('airports.departure'))</th>
        <th>@sortablelink('arr_airport_id', __('airports.arrival'))</th>
        <th>@sortablelink('dpt_time', 'STD')</th>
        <th>@sortablelink('arr_time', 'STA')</th>
        <th>@sortablelink('distance', 'Distance')</th>
        <th>@sortablelink('flight_time', 'Flight Time')</th>
      </tr>
    </table>
  </div>
</div>
<div class="row">
  @foreach($bids as $bid)
    <div class="col-lg-6 col-md-12">
      <div class="card">
        <div class="card-body" style="min-height: 0">
          <div class="row">
            <div class="col-sm-12">
              <div class="flex-row justify-content-between d-none d-lg-flex">
                <div class="">{{ $bid->flight->airline->name }}</div>
                <div>{{\App\Models\Enums\FlightType::label($bid->flight->flight_type)}}</div>
              </div>

              <div style="font-size: 32px; line-height: 32px; font-weight: 600; text-align: center">


                {{ $bid->flight->ident }}
                @if(filled($bid->flight->callsign) && !setting('simbrief.callsign', true))
                  {{ '| '. $bid->flight->atc }}
                @endif

              </div>

              <div class="text-center my-2 d-flex flex-row justify-content-center">
                <div>
                  <div style="font-size: 48px; line-height: 48px; font-weight: 600">
                    {{$bid->flight->dpt_airport_id}}
                  </div>
                  <div style="font-size: 24px; line-height: 24px;">
                    {{$bid->flight->dpt_time}}
                  </div>
                </div>
                <div class="mx-4 mt-0" style="font-size: 24px; line-height: 48px">to</div>
                <div>
                  <div style="font-size: 48px; line-height: 48px; font-weight: 600">
                    {{$bid->flight->arr_airport_id}}
                  </div>
                  <div style="font-size: 24px; line-height: 24px;">
                    {{$bid->flight->arr_time}}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3 align-top text-right">
              {{--
              !!! NOTE !!!
               Don't remove the "save_flight" class, or the x-id attribute.
               It will break the AJAX to save/delete

               "x-saved-class" is the class to add/remove if the bid exists or not
               If you change it, remember to change it in the in-array line as well
              --}}

            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              {{--
              <span class="title">{{ strtoupper(__('flights.dep')) }}&nbsp;</span>
              {{ optional($bid->flight->dpt_airport)->name ?? $bid->flight->dpt_airport_id }}
              (<a href="{{route('frontend.airports.show', ['id' => $bid->flight->dpt_airport_id])}}">{{$bid->flight->dpt_airport_id}}</a>)
              @if($bid->flight->dpt_time), {{ $bid->flight->dpt_time }}@endif
              <br/>
              <span class="title">{{ strtoupper(__('flights.arr')) }}&nbsp;</span>
              {{ optional($bid->flight->arr_airport)->name ?? $bid->flight->arr_airport_id }}
              (<a href="{{route('frontend.airports.show', ['id' => $bid->flight->arr_airport_id])}}">{{$bid->flight->arr_airport_id}}</a>)
              @if($bid->flight->arr_time), {{ $bid->flight->arr_time }}@endif
              <br/>
              @if(filled($bid->flight->callsign) && !setting('simbrief.callsign', true))
                <span class="title">{{ strtoupper(__('flights.callsign')) }}&nbsp;</span>
                {{ $bid->flight->atc }}
                <br/>
              @endif
              @if($bid->flight->distance)
                <span class="title">{{ strtoupper(__('common.distance')) }}&nbsp;</span>
                {{ $bid->flight->distance }} {{ setting('units.distance') }}
                <br/>
              @endif
              @if($bid->flight->level)
                <span class="title">{{ strtoupper(__('flights.level')) }}&nbsp;</span>
                {{ $bid->flight->level }} {{ setting('units.altitude') }}
                <br/>
              @endif
              --}}
              <div class="d-flex flex-row justify-content-between">
                <div class="d-flex flex-row"><span class="material-symbols-outlined">travel</span><span>@if($bid->aircraft_id) {{$bid->aircraft->icao}} ({{$bid->aircraft->registration}}) @else Not Assigned @endif</span></div>
                @if (setting('bids.expire_time') !== 0)
                <div class="d-flex flex-row">
                  @php
                    $time = \Carbon\Carbon::parse($bid->created_at)->addHours(setting('bids.expire_time'));
                    $expires = $time->diffInSeconds(\Carbon\Carbon::now());
                    $warning = $time->diffInHours(\Carbon\Carbon::now()) <= 2;
                  @endphp
                  <span class="material-symbols-outlined @if($warning) text-warning @endif">timer</span>
                  <span class="@if($warning) text-warning @endif">{{gmdate('H:i', $expires)}}</span>
                </div>
                  @endif
              </div>
            </div>
            <div class="col-sm-5">
              @if($bid->flight->route)
                <span class="title">{{ strtoupper(__('flights.route')) }}&nbsp;</span>
                {{ $bid->flight->route }}
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <a class="btn btn-sm btn-outline-info" href="{{ route('frontend.flights.show', [$bid->flight->id]) }}">More Info</a>
          @if ($acars_plugin)
            @if (isset($saved[$bid->flight->id]))
              <a href="vmsacars:bid/{{ $saved[$bid->flight->id] }}" class="btn btn-sm btn-outline-primary">Load in vmsACARS</a>
            @else
              <a href="vmsacars:flight/{{ $bid->flight->id }}" class="btn btn-sm btn-outline-primary">Load in vmsACARS</a>
            @endif
          @endif
          <!-- Simbrief enabled -->
          @if ($simbrief !== false)
            <!-- If this flight has a briefing, show the link to view it-->
            @if ($bid->flight->simbrief && $bid->flight->simbrief->user_id === $user->id)
              <a href="{{ route('frontend.simbrief.briefing', $bid->flight->simbrief->id) }}"
                 class="btn btn-sm btn-outline-primary">
                View Simbrief Flight Plan
              </a>
            @else
              <!-- Show button if the bids-only is disable, or if bids-only is enabled, they've saved it -->
              @if ($simbrief_bids === false || ($simbrief_bids === true && isset($saved[$bid->flight->id])))
                @php
                  $aircraft_id = isset($saved[$bid->flight->id]) ? App\Models\Bid::find($saved[$bid->flight->id])->aircraft_id : null;
                @endphp
                <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $bid->flight->id }}@if($aircraft_id)&aircraft_id={{ $aircraft_id }} @endif"
                   class="btn btn-sm btn-outline-primary">
                  Create Simbrief Flight Plan
                </a>
              @endif
            @endif
          @endif
            <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $bid->flight->id }}"
               class="btn btn-sm btn-outline-info">
              {{ __('pireps.newpirep') }}
            </a>
            @if (!setting('pilots.only_flights_from_current') || $bid->flight->dpt_airport_id == $user->current_airport->icao)
              <button class="btn btn-sm save_flight
                           {{ isset($saved[$bid->flight->id]) ? 'btn-success':'btn-outline-success' }}"
                      x-id="{{ $bid->flight->id }}"
                      x-saved-class="btn-success"
                      x-not-saved-class="btn-outline-success"
                      type="button"
                      title="@lang('flights.addremovebid')">
                {{isset($saved[$bid->flight->id]) ? "Remove Bid" : "Add Bid"}}
              </button>
            @endif
        </div>
      </div>
    </div>
  @endforeach
</div>

