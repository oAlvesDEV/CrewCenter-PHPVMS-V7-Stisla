@extends('app')
@section('title', __('home.welcome.title'))

@section('content')
@php
  $count = is_countable($users) ? $users->count() : 0;
@endphp

<section class="section">
  <div class="section-header text-center">
    <h1>Welcome to Fly Azores Virtual</h1>
  </div>

  <div class="section-body">

    {{-- HERO --}}
    <div class="hero bg-primary text-white py-5 rounded shadow-sm mb-5 text-center">
      <h2 class="mb-3">Discover the joy of flying with freedom, realism and community.</h2>
      <a href="{{ route('register') }}" class="btn btn-lg btn-light mt-3">
        <i class="fas fa-user-plus me-2"></i>Join Now
      </a>
    </div>
    {{-- NEWEST PILOTS --}}
    <div class="card mb-5 shadow-sm">
      <div class="card-header">
        <h4>👨‍✈️ @lang('common.newestpilots')</h4>
      </div>
      <div class="card-body">
        <div class="row">
          @forelse($users->take(4) as $user)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
              <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">

                  {{-- Avatar + Name --}}
                  <div class="d-flex align-items-center mb-3">
                    <img src="{{ $user->avatar ? $user->avatar->url : $user->gravatar(256) }}"
                         alt="avatar"
                         class="rounded-circle me-3"
                         style="width:64px;height:64px;object-fit:cover;border:2px solid #eee;">
                    <div class="min-w-0">
                      <h5 class="mb-0 text-truncate">
                        <a href="{{ route('frontend.profile.show', [$user->id]) }}" class="text-decoration-none text-dark">
                          {{ $user->name_private }}
                        </a>
                      </h5>
                      <div class="text-muted small text-truncate">
                        {{ $user->ident }}@if(filled($user->callsign)) • {{ $user->callsign }}@endif
                      </div>
                    </div>
                  </div>

                  {{-- Airline / Home / Current --}}
                  <div class="mb-3">
                    @if(optional($user->airline)->name)
                      <span class="badge bg-info text-white me-1 mb-1">🏢 {{ $user->airline->name }}</span>
                    @endif
                    @if($user->home_airport)
                      <span class="badge bg-secondary text-white me-1 mb-1">🏠 {{ $user->home_airport->icao }}</span>
                    @endif
                    @if($user->current_airport)
                      <span class="badge bg-dark text-white me-1 mb-1">🗺️ {{ $user->current_airport->icao }}</span>
                    @endif
                  </div>

                  {{-- Mini Stats --}}
                  <div class="d-flex justify-content-between text-center mb-3">
                    <div class="flex-fill border rounded p-2 mx-1 bg-light">
                      <div class="fw-bold">{{ $user->flights }}</div>
                      <small class="text-muted">{{ trans_choice('common.flight', 2) }}</small>
                    </div>
                    <div class="flex-fill border rounded p-2 mx-1 bg-light">
                      <div class="fw-bold">@minutestotime($user->flight_time)</div>
                      <small class="text-muted">@lang('flights.flighthours')</small>
                    </div>
                  </div>

                  {{-- Footer Action --}}
                  <div class="mt-auto">
                    <a href="{{ route('frontend.profile.show', [$user->id]) }}"
                       class="btn btn-outline-primary btn-sm w-100">
                      <i class="fas fa-id-card me-1"></i>@lang('common.profile')
                    </a>
                  </div>

                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-light text-center mb-0">
                — {{ __('No pilots to show yet') }} —
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- OPERATIONS (LIVE MAP) --}}
    <div class="card mb-5 shadow-sm">
      <div class="card-header">
      </div>
      <div class="card-body">
        @widget('liveMap')
      </div>
    </div>


  </div>
</section>
@endsection
