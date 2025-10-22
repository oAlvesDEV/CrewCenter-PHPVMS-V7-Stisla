<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
  Launch demo modal
</button>
<form method="get" action="{{ route('frontend.flights.search') }}">
  <div class="modal fade" id="searchModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('flights.search')</h1>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group search-form">
                @csrf
                <div>
                  <div class="form-group">
                    <div>@lang('common.airline')</div>
                    <select name="airline_id" id="airline_id" class="form-control form-select">
                      @foreach($airlines as $airline_id => $airline_label)
                        <option value="{{ $airline_id }}" @if(request()->get('airline_id') == $airline_id) selected @endif>{{ $airline_label }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="mt-3">
                  <div>@lang('flights.flighttype')</div>
                  <select name="flight_type" id="flight_type" class="form-control form-select">
                    @foreach($flight_types as $flight_type_id => $flight_type_label)
                      <option value="{{ $flight_type_id }}" @if(request()->get('flight_type') == $flight_type_id) selected @endif>{{ $flight_type_label }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mt-3">
                  <div>@lang('flights.flightnumber')</div>
                  <input type="text" name="flight_number" id="flight_number" class="form-control" value="{{ request()->get('flight_number') }}" />
                </div>

                <div class="mt-3">
                  <div>@lang('flights.code')</div>
                  <input type="text" name="route_code" id="route_code" class="form-control" value="{{ request()->get('route_code') }}" />
                </div>

                <div class="mt-3">
                  <div>@lang('airports.departure')</div>
                  <select name="dep_icao" id="dep_icao" class="form-control airport_search search_modal">
                  </select>
                </div>

                <div class="mt-3">
                  <div>@lang('airports.arrival')</div>
                  <select name="arr_icao" id="arr_icao" class="form-control airport_search search_modal">
                  </select>
                </div>

                <div class="mt-3">
                  <div>@lang('common.subfleet')</div>
                  <select name="subfleet_id" id="subfleet_id" class="form-control form-select">
                    @foreach($subfleets as $subfleet_id => $subfleet_label)
                      <option value="{{ $subfleet_id }}" @if(request()->get('subfleet_id') == $subfleet_id) selected @endif>{{ $subfleet_label }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">@lang('common.find')</button>
          <a class="btn btn-secondary" href="{{ route('frontend.flights.index') }}">@lang('common.reset')</a>
        </div>
      </div>
    </div>
  </div>
</form>
<h4 class="description">@lang('flights.search')</h4>

