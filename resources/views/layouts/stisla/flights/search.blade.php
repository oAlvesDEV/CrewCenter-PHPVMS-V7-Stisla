<h4 class="description">@lang('flights.search')</h4>
<div class="row">
  <div class="col-12">
    <div class="form-group search-form">
      {{ Form::open([
              'route' => 'frontend.flights.search',
              'method' => 'GET',
      ]) }}

      <div>
        <div class="form-group">
          <div>@lang('common.airline')</div>
          {{ Form::select('airline_id', $airlines, request()->airline_id , ['class' => 'form-control select2']) }}
        </div>
      </div>

      <div class="mt-3">
        <div>@lang('flights.flighttype')</div>
        {{ Form::select('flight_type', $flight_types, request()->flight_type , ['class' => 'form-control select2']) }}
      </div>

      <div class="mt-3">
        <div>@lang('flights.flightnumber')</div>
        {{ Form::text('flight_number', request()->flight_number, ['class' => 'form-control']) }}
      </div>

      <div class="mt-3">
        <div>@lang('flights.code')</div>
        {{ Form::text('route_code', request()->route_code, ['class' => 'form-control']) }}
      </div>

      <div class="mt-3">
        <div>@lang('airports.departure')</div>
        {{ Form::select('dep_icao', $airports, request()->dep_icao , ['class' => 'form-control airport_search']) }}
      </div>

      <div class="mt-3">
        <div>@lang('airports.arrival')</div>
        {{ Form::select('arr_icao', $airports, request()->arr_icao , ['class' => 'form-control airport_search']) }}
      </div>

      <div class="mt-3">
        <div>@lang('common.subfleet')</div>
        {{ Form::select('subfleet_id', $subfleets, request()->subfleet_id , ['class' => 'form-control select2']) }}
      </div>

      <div class="clear mt-3" style="margin-top: 10px;">
        {{ Form::submit(__('common.find'), ['class' => 'btn btn-outline-primary']) }}&nbsp;
        <a href="{{ route('frontend.flights.index') }}">@lang('common.reset')</a>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
