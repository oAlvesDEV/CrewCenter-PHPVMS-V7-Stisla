@extends('app')
@section('title', $airport->full_name)

@section('content')
<div class="container-fluid px-0">

  {{-- HERO HEADER --}}
  <div class="dash-hero p-4 p-md-5 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h1 class="mb-1">{{ $airport->full_name }}</h1>
        <div class="text-muted">
          ICAO: <strong>{{ $airport->icao }}</strong>
          @if(filled($airport->elevation))
            • Elevation: {{ $airport->elevation }} ft
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    {{-- WEATHER WIDGET --}}
    <div class="col-lg-5 mb-3">
      <div class="card-glass h-100">
        
          {{ Widget::Weather(['icao' => $airport->icao]) }}
      
      </div>
    </div>

    {{-- MAP + NOTES --}}
    <div class="col-lg-7 mb-3">
      <div class="card-glass h-100">
        <div class="header-bar">🗺️ Airspace</div>
        <div class="p-3">
          {{ Widget::AirspaceMap([
            'width' => '100%',
            'height' => '400px',
            'lat' => $airport->lat,
            'lon' => $airport->lon
          ]) }}

          @if(filled($airport->notes))
            <hr>
            <div class="text-muted">{!! $airport->notes !!}</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- FILE DOWNLOADS --}}
  @if(count($airport->files) > 0 && Auth::check())
    <div class="row mb-4">
      <div class="col-12">
        <div class="card-glass">
          <div class="header-bar">📂 {{ trans_choice('common.download', 2) }}</div>
          <div class="p-3">
            @include('downloads.table', ['files' => $airport->files])
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    {{-- INBOUND --}}
    <div class="col-lg-6 mb-4">
      <div class="card-glass h-100">
        <div class="header-bar">🛬 @lang('flights.inbound')</div>
        <div class="p-3">
          @php
            $inboundEmpty = !($inbound_flights ?? false) || (is_countable($inbound_flights) ? count($inbound_flights) === 0 : empty($inbound_flights));
          @endphp

          @if($inboundEmpty)
            <div class="text-center text-muted py-4">@lang('flights.none')</div>
          @else
            <div class="table-glass">
              <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle text-nowrap table-dva mb-0">
                  <thead>
                    <tr class="text-small header">
                      <th class="text-left">@lang('airports.ident')</th>
                      <th class="text-left">@lang('airports.departure')</th>
                      <th>@lang('flights.dep')</th>
                      <th>@lang('flights.arr')</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($inbound_flights as $flight)
                    <tr>
                      <td class="text-left">
                        <a href="{{ route('frontend.flights.show', [$flight->id]) }}">{{ $flight->ident }}</a>
                      </td>
                      <td class="text-left">
                        {{ optional($flight->dpt_airport)->name }}
                        (<a href="{{ route('frontend.airports.show', ['id' => $flight->dpt_airport_id]) }}">
                          {{ $flight->dpt_airport_id }}
                        </a>)
                      </td>
                      <td>{{ $flight->dpt_time }}</td>
                      <td>{{ $flight->arr_time }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- OUTBOUND --}}
    <div class="col-lg-6 mb-4">
      <div class="card-glass h-100">
        <div class="header-bar">🛫 @lang('flights.outbound')</div>
        <div class="p-3">
          @php
            $outboundEmpty = !($outbound_flights ?? false) || (is_countable($outbound_flights) ? count($outbound_flights) === 0 : empty($outbound_flights));
          @endphp

          @if($outboundEmpty)
            <div class="text-center text-muted py-4">@lang('flights.none')</div>
          @else
            <div class="table-glass">
              <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle text-nowrap table-dva mb-0">
                  <thead>
                    <tr class="text-small header">
                      <th class="text-left">@lang('airports.ident')</th>
                      <th class="text-left">@lang('airports.arrival')</th>
                      <th>@lang('flights.dep')</th>
                      <th>@lang('flights.arr')</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($outbound_flights as $flight)
                    <tr>
                      <td class="text-left">
                        <a href="{{ route('frontend.flights.show', [$flight->id]) }}">{{ $flight->ident }}</a>
                      </td>
                      <td class="text-left">
                        {{ optional($flight->arr_airport)->name }}
                        (<a href="{{ route('frontend.airports.show', ['id' => optional($flight->arr_airport)->icao]) }}">
                          {{ optional($flight->arr_airport)->icao }}
                        </a>)
                      </td>
                      <td>{{ $flight->dpt_time }}</td>
                      <td>{{ $flight->arr_time }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
