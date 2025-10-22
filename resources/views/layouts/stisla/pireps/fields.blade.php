{{--
NOTE ABOUT THIS VIEW

The fields that are marked "read-only", make sure the read-only status doesn't change!
If you make those fields editable, after they're in a read-only state, it can have
an impact on your stats and financials, and will require a recalculation of all the
flight reports that have been filed. You've been warned!
--}}

@if(!empty($pirep) && $pirep->read_only)
  <div class="row">
    <div class="col-sm-12">
      <div class="card-glass p-3 mb-3">
        <div class="alert alert-warning mb-0">
          @lang('pireps.fieldsreadonly')
        </div>
      </div>
    </div>
  </div>
@endif

<div class="row">
  {{-- LEFT COLUMN --}}
  <div class="col-12 col-lg-8">

    {{-- Flight Information --}}
    <div class="card-glass form-container mb-3">
      <div class="form-title"><i class="fas fa-info-circle"></i>&nbsp;@lang('pireps.flightinformations')</div>
      <div class="form-container-body p-3">
        <div class="row">
          {{-- Airline --}}
          <div class="col-sm-4">
            <label for="airline_id" class="form-label">@lang('common.airline')</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">{{ $pirep->airline->name }}</p>
              <input type="hidden" name="airline_id" value="{{ $pirep->airline_id }}" />
            @else
              <select name="airline_id" id="airline_id" class="form-control select2">
                @foreach($airline_list as $airline_id => $airline_label)
                  <option value="{{ $airline_id }}" @if(!empty($pirep) && $airline_id === $pirep->airline_id) selected @endif>
                    {{ $airline_label }}
                  </option>
                @endforeach
              </select>
              <small class="text-danger d-block">{{ $errors->first('airline_id') }}</small>
            @endif
          </div>

          {{-- Flight Ident / Code / Leg --}}
          <div class="col-sm-4">
            <label for="flight_number" class="form-label">@lang('pireps.flightident')</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">
                {{ $pirep->ident }}
                <input type="hidden" name="flight_number" value="{{ $pirep->flight_number }}" />
                <input type="hidden" name="flight_code" value="{{ $pirep->flight_code }}" />
                <input type="hidden" name="flight_leg" value="{{ $pirep->flight_leg }}" />
              </p>
            @else
              <div class="input-group input-group-sm mb-2">
                <input type="text" name="flight_number" id="flight_number" class="form-control"
                       value="{{ !empty($pirep) ? $pirep->flight_number : old('flight_number') }}"
                       placeholder="@lang('flights.flightnumber')">
              </div>
              <div class="input-group input-group-sm mb-2">
                <input type="text" name="route_code" id="route_code" class="form-control"
                       value="{{ !empty($pirep) ? $pirep->route_code : old('route_code') }}"
                       placeholder="@lang('pireps.codeoptional')">
              </div>
              <div class="input-group input-group-sm">
                <input type="text" name="route_leg" id="route_leg" class="form-control"
                       value="{{ !empty($pirep) ? $pirep->route_leg : old('route_leg') }}"
                       placeholder="@lang('pireps.legoptional')">
              </div>
              <small class="text-danger d-block">{{ $errors->first('flight_number') }}</small>
              <small class="text-danger d-block">{{ $errors->first('route_code') }}</small>
              <small class="text-danger d-block">{{ $errors->first('route_leg') }}</small>
            @endif
          </div>

          {{-- Flight Type --}}
          <div class="col-sm-4">
            <label for="flight_type" class="form-label">@lang('flights.flighttype')</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">{{ \App\Models\Enums\FlightType::label($pirep->flight_type) }}</p>
              <input type="hidden" name="flight_type" value="{{ $pirep->flight_type }}" />
            @else
              <select name="flight_type" id="flight_type" class="form-control select2">
                @foreach(\App\Models\Enums\FlightType::select() as $flight_type_id => $flight_type_label)
                  <option value="{{ $flight_type_id }}" @if(!empty($pirep) && $pirep->flight_type == $flight_type_id) selected @endif>
                    {{ $flight_type_label }}
                  </option>
                @endforeach
              </select>
              <small class="text-danger d-block">{{ $errors->first('flight_type') }}</small>
            @endif
          </div>
        </div>

        {{-- Time & Level --}}
        <div class="row mt-2">
          {{-- Flight Time --}}
          <div class="col-sm-6">
            <label for="hours" class="form-label">@lang('flights.flighttime')</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">
                {{ $pirep->hours.' '.trans_choice('common.hour', $pirep->hours) }},
                {{ $pirep->minutes.' '.trans_choice('common.minute', $pirep->minutes) }}
                <input type="hidden" name="hours" value="{{ $pirep->hours }}" />
                <input type="hidden" name="minutes" value="{{ $pirep->minutes }}" />
              </p>
            @else
              <div class="d-flex gap-2" style="max-width:420px;">
                <input type="number" name="hours" id="hours" min="0" class="form-control"
                       value="{{ !empty($pirep) ? $pirep->hours : old('hours') }}"
                       placeholder="{{ trans_choice('common.hour', 2) }}">
                <input type="number" name="minutes" id="minutes" min="0" class="form-control"
                       value="{{ !empty($pirep) ? $pirep->minutes : old('minutes') }}"
                       placeholder="{{ trans_choice('common.minute', 2) }}">
              </div>
              <small class="text-danger d-block">{{ $errors->first('hours') }}</small>
              <small class="text-danger d-block">{{ $errors->first('minutes') }}</small>
            @endif
          </div>

          {{-- Level --}}
          <div class="col-sm-6">
            <label for="level" class="form-label">
              @lang('flights.level') ({{ config('phpvms.internal_units.altitude') }})
            </label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">{{ $pirep->level }}</p>
            @else
              <input type="number" name="level" id="level" step="0.01" min="0" class="form-control"
                     value="{{ !empty($pirep) ? $pirep->level : old('level') }}">
              <small class="text-danger d-block">{{ $errors->first('level') }}</small>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Dep/Arr --}}
    <div class="card-glass form-container mb-3">
      <div class="form-title"><i class="fas fa-globe"></i>&nbsp;@lang('pireps.deparrinformations')</div>
      <div class="form-container-body p-3">
        <div class="row">
          {{-- Departure --}}
          <div class="col-sm-6">
            <label for="dpt_airport_id" class="form-label">@lang('airports.departure')</label>
            @if(!empty($pirep) && ($pirep->read_only || request()->has('flight_id')))
              <p class="form-readonly">
                {{ $pirep->dpt_airport->name }}
                (<a href="{{ route('frontend.airports.show', ['id' => $pirep->dpt_airport->icao]) }}">{{ $pirep->dpt_airport->icao }}</a>)
              </p>
              <input type="hidden" name="dpt_airport_id" value="{{ $pirep->dpt_airport_id }}" />
            @else
              <select name="dpt_airport_id" id="dpt_airport_id" class="form-control airport_search">
                @foreach($airport_list as $dpt_airport_id => $dpt_airport_label)
                  <option value="{{ $dpt_airport_id }}" @if(!empty($pirep) && $pirep->dpt_airport_id == $dpt_airport_id) selected @endif>
                    {{ $dpt_airport_label }}
                  </option>
                @endforeach
              </select>
              <small class="text-danger d-block">{{ $errors->first('dpt_airport_id') }}</small>
            @endif
          </div>

          {{-- Arrival --}}
          <div class="col-sm-6">
            <label for="arr_airport_id" class="form-label">@lang('airports.arrival')</label>
            @if(!empty($pirep) && ($pirep->read_only || request()->has('flight_id')))
              <p class="form-readonly">
                {{ $pirep->arr_airport->name }}
                (<a href="{{ route('frontend.airports.show', ['id' => $pirep->arr_airport->icao]) }}">{{ $pirep->arr_airport->icao }}</a>)
              </p>
              <input type="hidden" name="arr_airport_id" value="{{ $pirep->arr_airport_id }}" />
            @else
              <select name="arr_airport_id" id="arr_airport_id" class="form-control airport_search">
                @foreach($airport_list as $arr_airport_id => $arr_airport_label)
                  <option value="{{ $arr_airport_id }}" @if(!empty($pirep) && $pirep->arr_airport_id == $arr_airport_id) selected @endif>
                    {{ $arr_airport_label }}
                  </option>
                @endforeach
              </select>
              <small class="text-danger d-block">{{ $errors->first('arr_airport_id') }}</small>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Aircraft --}}
    <div class="card-glass form-container mb-3">
      <div class="form-title"><i class="fab fa-avianex"></i>&nbsp;@lang('pireps.aircraftinformations')</div>
      <div class="form-container-body p-3">
        <div class="row">
          {{-- Aircraft --}}
          <div class="col-sm-4">
            <label for="aircraft_id" class="form-label">@lang('common.aircraft')</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">{{ $pirep->aircraft->name }}</p>
              <input type="hidden" name="aircraft_id" value="{{ $pirep->aircraft_id }}" />
            @else
              <select name="aircraft_id" id="aircraft_select" class="form-control select2">
                @foreach($aircraft_list as $subfleet => $sf_aircraft)
                  @if ($subfleet === '')
                    <option value=""></option>
                  @else
                    @foreach($sf_aircraft as $aircraft_id => $aircraft_label)
                      <option value="{{ $aircraft_id }}" @if(!empty($pirep) && $pirep->aircraft_id == $aircraft_id) selected @endif>
                        {{ $aircraft_label }}
                      </option>
                    @endforeach
                  @endif
                @endforeach
              </select>
              <small class="text-danger d-block">{{ $errors->first('aircraft_id') }}</small>
            @endif
          </div>

          {{-- Block Fuel --}}
          <div class="col-sm-4">
            <label for="block_fuel" class="form-label">@lang('pireps.block_fuel') ({{ setting('units.fuel') }})</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">{{ $pirep->block_fuel }}</p>
            @else
              <input type="number" name="block_fuel" id="block_fuel" min="0" step="0.01" class="form-control"
                     value="{{ !empty($pirep) ? $pirep->block_fuel : old('block_fuel') }}">
              <small class="text-danger d-block">{{ $errors->first('block_fuel') }}</small>
            @endif
          </div>

          {{-- Fuel Used --}}
          <div class="col-sm-4">
            <label for="fuel_used" class="form-label">@lang('pireps.fuel_used') ({{ setting('units.fuel') }})</label>
            @if(!empty($pirep) && $pirep->read_only)
              <p class="form-readonly">{{ $pirep->fuel_used }}</p>
            @else
              <input type="number" name="fuel_used" id="fuel_used" min="0" step="0.01" class="form-control"
                     value="{{ !empty($pirep) ? $pirep->fuel_used : old('fuel_used') }}">
              <small class="text-danger d-block">{{ $errors->first('fuel_used') }}</small>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Fares --}}
    <div id="fares_container" class="card-glass form-container mb-3">
      <div class="form-title"><i class="fas fa-dollar-sign"></i>&nbsp;@lang('common.fares')</div>
      <div class="form-container-body p-0">
        @include('pireps.fares')
      </div>
    </div>

    {{-- Route --}}
    <div class="card-glass form-container mb-3">
      <div class="form-title"><i class="far fa-comments"></i>&nbsp;@lang('flights.route')</div>
      <div class="form-container-body p-3">
        <textarea name="route" id="route" class="form-control"
                  placeholder="@lang('flights.route')"
                  rows="3">@if(!empty($pirep)){{ $pirep->route }}@else{{ old('route') }}@endif</textarea>
        <small class="text-danger d-block">{{ $errors->first('route') }}</small>
      </div>
    </div>

    {{-- Notes --}}
    <div class="card-glass form-container mb-3">
      <div class="form-title"><i class="far fa-comments"></i>&nbsp;{{ trans_choice('common.remark', 2) }}</div>
      <div class="form-container-body p-3">
        <textarea name="notes" id="notes" class="form-control"
                  placeholder="{{ trans_choice('common.note', 2) }}"
                  rows="3">@if(!empty($pirep)){{ $pirep->notes }}@else{{ old('notes') }}@endif</textarea>
        <small class="text-danger d-block">{{ $errors->first('notes') }}</small>
      </div>
    </div>
  </div>

  {{-- RIGHT COLUMN --}}
  <div class="col-12 col-lg-4">
    <div class="card-glass form-container mb-3">
      <div class="form-title"><i class="fab fa-wpforms"></i>&nbsp;{{ trans_choice('common.field', 2) }}</div>
      <div class="form-container-body p-0">
        <table class="table table-striped table-dva mb-0">
          @if(isset($pirep) && $pirep->fields)
            @each('pireps.custom_fields', $pirep->fields, 'field')
          @else
            @each('pireps.custom_fields', $pirep_fields, 'field')
          @endif
        </table>
      </div>
    </div>
  </div>
</div>

{{-- ACTIONS --}}
<div class="row">
  <div class="col-sm-12">
    <div class="d-flex justify-content-end">
      <div class="btn-toolbar gap-2" role="toolbar">
        <input type="hidden" name="flight_id" value="{{ !empty($pirep) ? $pirep->flight_id : '' }}"/>
        <input type="hidden" name="sb_id" value="{{ $simbrief_id }}"/>

        @if(isset($pirep) && !$pirep->read_only)
          <button name="submit" type="submit" class="btn btn-dva-danger" value="Delete"
                  onclick="return confirm('Are you sure you want to delete this PIREP?')">
            @lang('pireps.deletepirep')
          </button>
        @endif

        <button name="submit" type="submit" class="btn btn-dva-secondary" value="Save">
          @lang('pireps.savepirep')
        </button>

        @if(!isset($pirep) || (filled($pirep) && !$pirep->read_only))
          <button name="submit" type="submit" class="btn btn-dva-primary" value="Submit">
            @lang('pireps.submitpirep')
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
