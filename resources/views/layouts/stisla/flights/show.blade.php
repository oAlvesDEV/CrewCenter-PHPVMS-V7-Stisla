@extends('app')
@section('title', trans_choice('common.flight', 1).' '.$flight->ident)

@section('content')
<div class="section">
  <div class="section-header">
    <h1>{{ $flight->ident }}
      @if(filled($flight->callsign) && !setting('simbrief.callsign', true))
        {{ '| '. $flight->atc }}
      @endif
    </h1>
    <div class="section-header-breadcrumb">
      @if ($acars_plugin && $bid)
        <a href="vmsacars:bid/{{$bid->id}}" class="btn btn-primary btn-sm">Load in vmsACARS</a>
      @elseif ($acars_plugin)
        <a href="vmsacars:flight/{{$flight->id}}" class="btn btn-primary btn-sm">Load in vmsACARS</a>
      @endif
    </div>
  </div>

  <div class="row">
    <div class="col-8">

      <!-- Departure & Arrival Info Boxes -->
      <div class="row">
        <div class="col-md-4 col-12">
          <div class="card card-info">
            <div class="card-body text-center">
              <h5>@lang('common.departure')</h5>
              <p class="mb-1"><strong>{{ $flight->dpt_airport_id }}</strong></p>
              <small>{{ optional($flight->dpt_airport)->name ?? $flight->dpt_airport_id }}</small>
              <p class="mt-1 badge badge-primary">{{ $flight->dpt_time }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="card card-success">
            <div class="card-body text-center">
              <h5>@lang('common.arrival')</h5>
              <p class="mb-1"><strong>{{ $flight->arr_airport_id }}</strong></p>
              <small>{{ optional($flight->arr_airport)->name ?? $flight->arr_airport_id }}</small>
              <p class="mt-1 badge badge-success">{{ $flight->arr_time }}</p>
            </div>
          </div>
        </div>
        @if($flight->alt_airport_id)
        <div class="col-md-4 col-12">
          <div class="card card-warning">
            <div class="card-body text-center">
              <h5>@lang('flights.alternateairport')</h5>
              <p class="mb-1"><strong>{{ $flight->alt_airport_id }}</strong></p>
              <small>{{ optional($flight->alt_airport)->name ?? $flight->alt_airport_id }}</small>
            </div>
          </div>
        </div>
        @endif
      </div>

      <!-- Flight Details Card -->
      <div class="card mt-3">
        <div class="card-header">
          <h4>Flight Details</h4>
        </div>
        <div class="card-body">
          <table class="table table-striped table-hover">
            @if(filled($flight->route))
            <tr>
              <th>@lang('flights.route')</th>
              <td>{{ $flight->route }}</td>
            </tr>
            @endif
            @if(filled($flight->callsign) && !setting('simbrief.callsign', true))
            <tr>
              <th>@lang('flights.callsign')</th>
              <td>{{ $flight->atc }}</td>
            </tr>
            @endif
            @if(filled($flight->notes))
            <tr>
              <th>{{ trans_choice('common.note', 2) }}</th>
              <td>{{ $flight->notes }}</td>
            </tr>
            @endif
          </table>
        </div>
      </div>

      <!-- Flight Map Card -->
      <div class="card mt-3">
        <div class="card-header">
          <h4>Flight Map</h4>
        </div>
        <div class="card-body">
          @include('flights.map')
        </div>
      </div>

    </div>

    <div class="col-4">

      <!-- Weather Cards -->
      <div class="card card-primary">
        <div class="card-header">
          <h4>Weather</h4>
        </div>
        <div class="card-body">
          <h6 class="text-info">{{$flight->dpt_airport_id}} @lang('common.metar')</h6>
          {{ Widget::Weather(['icao' => $flight->dpt_airport_id]) }}
          <hr>
          <h6 class="text-success">{{$flight->arr_airport_id}} @lang('common.metar')</h6>
          {{ Widget::Weather(['icao' => $flight->arr_airport_id]) }}
          @if ($flight->alt_airport_id)
          <hr>
          <h6 class="text-warning">{{$flight->alt_airport_id}} @lang('common.metar')</h6>
          {{ Widget::Weather(['icao' => $flight->alt_airport_id]) }}
          @endif
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
