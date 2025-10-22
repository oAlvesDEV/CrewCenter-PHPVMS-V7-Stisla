<div class="row">
  <div class="col-md-12">
    <div class="card shadow-sm border-0">
      <div class="card-body">

        <div id="map" class="rounded-lg border" 
             style="width: {{ $config['width'] }}; height: {{ $config['height'] }}; overflow: hidden;">
          
          {{-- Info Box --}}
          <div id="map-info-box" class="p-3 bg-white rounded shadow position-absolute bottom-0 start-0 end-0 mb-3 mx-3"
               rv-show="pirep.id" 
               style="backdrop-filter: blur(8px); background: rgba(255, 255, 255, 0.9);">

            <div class="row align-items-center">
              <div class="col-md-4">
                <h5 class="fw-bold mb-1">
                  <a rv-href="pirep.id | prepend '{{url('/pireps/')}}/'" target="_blank" class="text-primary">
                    { pirep.airline.icao }{ pirep.flight_number }
                  </a>
                </h5>
                <p class="text-muted small mb-0">
                  { pirep.dpt_airport.name } ({ pirep.dpt_airport.icao }) 
                  @lang('common.to')
                  { pirep.arr_airport.name } ({ pirep.arr_airport.icao })
                </p>
              </div>

              <div class="col-md-4 border-start border-end">
                <p class="small mb-0">
                  <strong>@lang('common.status'):</strong> { pirep.status_text }<br/>
                  <strong>@lang('flights.flighttime'):</strong> { pirep.flight_time | time_hm }<br/>
                  <strong>@lang('common.distance'):</strong> 
                  { pirep.position.distance.{{setting('units.distance')}} } /
                  { pirep.planned_distance.{{setting('units.distance')}} }
                </p>
              </div>

              <div class="col-md-4">
                <p class="small mb-0">
                  <strong>@lang('widgets.livemap.groundspeed'):</strong> { pirep.position.gs }<br/>
                  <strong>@lang('widgets.livemap.altitude'):</strong> { pirep.position.altitude }<br/>
                  <strong>@lang('widgets.livemap.heading'):</strong> { pirep.position.heading }
                </p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@if($config['table'] === true)
  <div class="my-4"></div>

  <div id="live_flights" class="row">
    <div class="col-md-12">

      {{-- No flights state --}}
      <div rv-hide="has_data" class="card text-center border-0 shadow-sm bg-light py-5">
        <div class="card-body">
          <h6 class="text-muted mb-0">@lang('widgets.livemap.noflights')</h6>
        </div>
      </div>

      {{-- Table card --}}
      <div rv-show="has_data" class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-2">
          <h6 class="mb-0"><i class="fas fa-plane-departure me-2"></i>Voos ativos</h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="live_flights_table" class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>{{ trans_choice('common.flight', 2) }}</th>
                  <th>@lang('common.departure')</th>
                  <th>@lang('common.arrival')</th>
                  <th>@lang('common.aircraft')</th>
                  <th>@lang('widgets.livemap.altitude')</th>
                  <th>@lang('widgets.livemap.gs')</th>
                  <th>@lang('widgets.livemap.distance')</th>
                  <th>@lang('common.status')</th>
                </tr>
              </thead>
              <tbody>
                <tr rv-each-pirep="pireps">
                  <td><a href="#top_anchor" rv-on-click="controller.focusMarker" class="text-decoration-none text-primary fw-bold">{ pirep.ident }</a></td>
                  <td><span rv-title="pirep.dpt_airport.name">{ pirep.dpt_airport.icao }</span></td>
                  <td><span rv-title="pirep.arr_airport.name">{ pirep.arr_airport.icao }</span></td>
                  <td>{ pirep.aircraft.registration }</td>
                  <td>{ pirep.position.altitude }</td>
                  <td>{ pirep.position.gs }</td>
                  <td>{ pirep.position.distance.{{ setting('units.distance') }} | fallback 0 } / { pirep.planned_distance.{{ setting('units.distance') }} | fallback 0 }</td>
                  <td><span class="badge bg-info text-dark">{ pirep.status_text }</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
@endif

@section('scripts')
  <script>
    phpvms.map.render_live_map({
      center: ['{{ $center[0] }}', '{{ $center[1] }}'],
      zoom: '{{ $zoom }}',
      aircraft_icon: '{!! public_asset('/assets/img/acars/aircraft.png') !!}',
      refresh_interval: {{ setting('acars.update_interval', 60) }},
      units: '{{ setting('units.distance') }}',
      flown_route_color: '#067ec1',
      leafletOptions: { scrollWheelZoom: false },
    });
  </script>
@endsection
