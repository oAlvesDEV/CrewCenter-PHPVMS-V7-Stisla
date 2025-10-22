<div id="map" style="width: {{ $config['width'] }}; height: {{ $config['height'] }}">
    <div id="map-info-box" class="map-info-box" rv-show="pirep" style="width: {{ $config['width'] }};">
        <div style="float: left; width: 50%;">
            <h3 style="margin: 0" id="map_flight_id">
                <a rv-href="pirep.id | prepend '{{url('/pireps/')}}/'" target="_blank">
                    { pirep.airline.icao }{ pirep.flight_number }
                </a>
            </h3>
            <p id="map_flight_info">
                { pirep.dpt_airport.name } ({ pirep.dpt_airport.icao })
                @lang('common.to')
                { pirep.arr_airport.name } ({ pirep.arr_airport.icao })
            </p>
        </div>
        <div style="float: right; margin-left: 30px; margin-right: 30px;">
            <p id="map_flight_stats_right">
                @lang('widgets.livemap.groundspeed'): <span style="font-weight: bold">{ pirep.position.gs }</span><br />
                @lang('widgets.livemap.altitude'): <span style="font-weight: bold">{ pirep.position.altitude }</span><br />
                @lang('widgets.livemap.heading'): <span style="font-weight: bold">{ pirep.position.heading }</span><br />
            </p>
        </div>
        <div style="float: right; margin-left: 30px;">
            <p id="map_flight_stats_middle">
                @lang('common.status'): <span style="font-weight: bold">{ pirep.status_text }</span><br />
                @lang('flights.flighttime'): <span style="font-weight: bold">{ pirep.flight_time | time_hm }</span><br />
                @lang('common.distance'): <span style="font-weight: bold">{ pirep.position.distance.{{setting('units.distance')}} }</span> /
                <span style="font-weight: bold">{ pirep.planned_distance.{{setting('units.distance')}} }</span>
            </p>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        phpvms.map.render_live_map({
            center: ['{{ $center[0] }}', '{{ $center[1] }}'],
            zoom: '{{ $zoom }}',
            aircraft_icon: '{!! public_asset('/assets/img/acars/aircraft.png') !!}',
            units: '{{ setting('units.distance') }}',
        });
    </script>
@endsection
