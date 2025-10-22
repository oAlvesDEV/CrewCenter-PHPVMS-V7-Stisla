@extends('app')
@section('title', trans_choice('common.pirep', 1).' '.$pirep->ident)

@section('content')
<div class="section-body">

  {{-- HERO --}}
@php
  $unit = setting('units.distance');
  $distVal = optional($pirep->distance)->{$unit};
@endphp

<div class="row mb-4">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
        <div>
          <div class="mb-2">
            <span class="badge badge-primary">🧾 {{ __('PIREP') }}</span>
            <span class="badge badge-info">✈️ {{ $pirep->ident }}</span>
            <span class="badge badge-success">🛫 {{ $pirep->dpt_airport_id }} → 🛬 {{ $pirep->arr_airport_id }}</span>
          </div>
          <h3 class="card-title mb-1">{{ $pirep->ident }} — {{ $pirep->dpt_airport_id }} → {{ $pirep->arr_airport_id }}</h3>
          <small class="text-muted">
            {{ optional($pirep->created_at)->toDayDateTimeString() }}
            @if(is_numeric($distVal))
              • {{ number_format($distVal) }} {{ strtoupper($unit) }}
            @endif
          </small>
        </div>
        <div class="mt-2 mt-md-0">
          @if (!empty($pirep->simbrief))
            <a href="{{ route('frontend.simbrief.briefing', [$pirep->simbrief->id]) }}"
               class="btn btn-outline-primary btn-icon icon-left">
              <i class="fas fa-file-alt"></i> View SimBrief
            </a>
          @endif

          @php $user = auth()->user(); @endphp
          @if(!$pirep->read_only && $user && $pirep->user_id === $user->id)
            <a href="{{ route('frontend.pireps.edit', $pirep->id) }}" class="btn btn-outline-info btn-icon icon-left">
              <i class="fas fa-edit"></i> @lang('common.edit')
            </a>
            <form method="post" action="{{ route('frontend.pireps.submit', $pirep->id) }}" class="d-inline">
              @csrf
              <button class="btn btn-outline-success btn-icon icon-left"><i class="fas fa-check"></i> @lang('common.submit')</button>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  {{-- LEFT 8 --}}
  <div class="col-lg-8">

    {{-- Departure / Arrival summary --}}
    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="card card-primary shadow-sm">
          <div class="card-header">
            <h6 class="mb-0">{{ __('Departure') }}</h6>
          </div>
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <strong>{{ optional($pirep->dpt_airport)->location }}</strong><br>
              <small>
                <a href="{{ route('frontend.airports.show', $pirep->dpt_airport_id) }}">
                  {{ optional($pirep->dpt_airport)->full_name ?? $pirep->dpt_airport_id }}
                </a>
              </small>
              @if($pirep->block_off_time)
                <div class="text-muted small">{{ $pirep->block_off_time->toDayDateTimeString() }}</div>
              @endif
            </div>
            <div class="display-4">🛫</div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card card-success shadow-sm">
          <div class="card-header">
            <h6 class="mb-0">{{ __('Arrival') }}</h6>
          </div>
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="text-right">
              <strong>{{ optional($pirep->arr_airport)->location }}</strong><br>
              <small>
                <a href="{{ route('frontend.airports.show', $pirep->arr_airport_id) }}">
                  {{ optional($pirep->arr_airport)->full_name ?? $pirep->arr_airport_id }}
                </a>
              </small>
              @if($pirep->block_on_time)
                <div class="text-muted small">{{ $pirep->block_on_time->toDayDateTimeString() }}</div>
              @endif
            </div>
            <div class="display-4">🛬</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Progress --}}
    @if(!empty($pirep->distance))
    <div class="card mb-3 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">📍 {{ __('Progress') }}</h6>
      </div>
      <div class="card-body">
        <div class="progress mb-2" style="height: 10px;">
          <div class="progress-bar bg-info" role="progressbar" style="width: {{ (float)$pirep->progress_percent }}%;"></div>
        </div>
        <div class="d-flex justify-content-between small text-muted">
          <span>{{ $pirep->dpt_airport_id }}</span>
          <span>{{ number_format((float)$pirep->progress_percent, 0) }}%</span>
          <span>{{ $pirep->arr_airport_id }}</span>
        </div>
      </div>
    </div>
    @endif

    {{-- Map --}}
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">🗺️ {{ __('Route Map') }}</h6>
      </div>
      <div class="card-body p-2">
        @include('pireps.map')
      </div>
    </div>

    {{-- ACARS logs --}}
    @php
      $hasLogs = is_iterable($pirep->acars_logs ?? null) && count($pirep->acars_logs) > 0;
    @endphp
    @if($hasLogs)
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">📒 @lang('pireps.flightlog')</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-hover table-striped mb-0">
            <tbody>
              @foreach($pirep->acars_logs->sortBy('created_at') as $log)
              <tr>
                <td class="text-muted small" style="white-space:nowrap;">{{ show_datetime($log->created_at) }}</td>
                <td>{{ $log->log }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif

    {{-- OFP --}}
    @if(!empty($pirep->simbrief))
    <div class="card mb-5 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">📄 OFP</h6>
      </div>
      <div class="card-body p-0" style="height:600px; overflow:auto;">
        {!! optional($pirep->simbrief->xml->text)->plan_html !!}
      </div>
    </div>
    @endif

  </div>

  {{-- RIGHT 4 --}}
  <div class="col-lg-4">

    {{-- Status / meta --}}
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">ℹ️ {{ __('Details') }}</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-borderless mb-0">
          <tbody>
            <tr>
              <td class="text-muted small" style="width:38%;">@lang('common.state')</td>
              <td><span class="badge badge-info">{{ PirepState::label($pirep->state) }}</span></td>
            </tr>
            @if ($pirep->state !== PirepState::DRAFT)
            <tr>
              <td class="text-muted small">@lang('common.status')</td>
              <td><span class="badge badge-info">{{ PirepStatus::label($pirep->status) }}</span></td>
            </tr>
            @endif
            <tr>
              <td class="text-muted small">@lang('pireps.source')</td>
              <td>{{ PirepSource::label($pirep->source) }}</td>
            </tr>
            <tr>
              <td class="text-muted small">@lang('flights.flighttype')</td>
              <td>{{ \App\Models\Enums\FlightType::label($pirep->flight_type) }}</td>
            </tr>
            @if(filled($pirep->route))
            <tr>
              <td class="text-muted small">@lang('pireps.filedroute')</td>
              <td class="font-monospace">{{ $pirep->route }}</td>
            </tr>
            @endif
            @if(filled($pirep->notes))
            <tr>
              <td class="text-muted small">{{ trans_choice('common.note', 2) }}</td>
              <td>{{ $pirep->notes }}</td>
            </tr>
            @endif
            @if($pirep->score)
            <tr>
              <td class="text-muted small">Score</td>
              <td>{{ number_format((float)$pirep->score) }}</td>
            </tr>
            @endif
            @if($pirep->landing_rate)
            <tr>
              <td class="text-muted small">Landing Rate</td>
              <td>{{ number_format((float)$pirep->landing_rate) }} fpm</td>
            </tr>
            @endif
            <tr>
              <td class="text-muted small">@lang('pireps.filedon')</td>
              <td>{{ show_datetime($pirep->created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- Custom fields --}}
    @php
      $hasFields = is_iterable($pirep->fields ?? null) && count($pirep->fields) > 0;
    @endphp
    @if($hasFields)
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">🧾 {{ trans_choice('common.field', 2) }}</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-borderless mb-0">
          <thead>
            <tr>
              <th>@lang('common.name')</th>
              <th>{{ trans_choice('common.value', 1) }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pirep->fields as $field)
            <tr>
              <td class="text-muted small">{{ $field->name }}</td>
              <td>{{ $field->value }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Fares --}}
    @php
      $hasFares = is_iterable($pirep->fares ?? null) && count($pirep->fares) > 0;
    @endphp
    @if($hasFares)
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">💺 {{ trans_choice('pireps.fare', 2) }}</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-borderless mb-0">
          <thead>
            <tr>
              <th>@lang('pireps.class')</th>
              <th class="text-right">@lang('pireps.count')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pirep->fares as $fare)
            <tr>
              <td>{{ $fare->name }} ({{ $fare->code }})</td>
              <td class="text-right">{{ $fare->count }}</td>
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
@endsection
