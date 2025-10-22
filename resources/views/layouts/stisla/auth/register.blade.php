@extends('app')
@section('title', __('auth.register'))

{{-- Optional: give the page the same background treatment as dashboard --}}
@section('body_class', 'login-page')

@section('content')
<div class="container-fluid px-0">

  {{-- HERO (same feel as dashboard) --}}
  <div class="dash-hero p-4 p-md-5 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h1 class="mb-1">Join FLY AZORES VA</h1>
        <div class="text-muted">
          {{ __('Create your pilot profile to start flying with us.') }}
        </div>
      </div>
    </div>
  </div>

  {{-- FORM CARD --}}
  <div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9 col-md-10">
      <div class="card-glass p-0">

        <div class="header-bar">
          ✍️ {{ __('auth.register') }}
        </div>

        <div class="p-3 p-md-4">
          <form method="post" action="{{ url('/register') }}" class="form-signin">
            @csrf

            {{-- Identity --}}
            <div class="row">
              <div class="col-md-6">
                <label for="name" class="form-label mb-1">{{ __('auth.fullname') }}</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" autocomplete="name">
                @error('name')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mt-3 mt-md-0">
                <label for="email" class="form-label mb-1">{{ __('auth.emailaddress') }}</label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" autocomplete="email">
                @error('email')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Org / Location --}}
            <div class="row mt-3">
              <div class="col-md-6">
                <label for="airline_id" class="form-label mb-1">{{ __('common.airline') }}</label>
                <select name="airline_id" id="airline_id"
                        class="form-control select2 @error('airline_id') is-invalid @enderror"
                        style="width:100%">
                  @foreach($airlines as $airline_id => $airline_label)
                    <option value="{{ $airline_id }}" @selected($airline_id === old('airline_id'))>
                      {{ $airline_label }}
                    </option>
                  @endforeach
                </select>
                @error('airline_id')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mt-3 mt-md-0">
                <label for="home_airport_id" class="form-label mb-1">{{ __('airports.home') }}</label>
                <select name="home_airport_id" id="home_airport_id"
                        class="form-control airport_search @if($hubs_only) hubs_only @endif @error('airport_id') is-invalid @enderror"
                        style="width:100%">
                  @foreach($airports as $airport_id => $airport_label)
                    <option value="{{ $airport_id }}" @selected($airport_id === old('home_airport_id'))>
                      {{ $airport_label }}
                    </option>
                  @endforeach
                </select>
                @error('home_airport_id')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Country / Timezone --}}
            <div class="row mt-3">
              <div class="col-md-6">
                <label for="country" class="form-label mb-1">{{ __('common.country') }}</label>
                <select name="country" id="country"
                        class="form-control select2 @error('country') is-invalid @enderror"
                        style="width:100%">
                  @foreach($countries as $country_id => $country_label)
                    <option value="{{ $country_id }}" @selected($country_id === old('country'))>
                      {{ $country_label }}
                    </option>
                  @endforeach
                </select>
                @error('country')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mt-3 mt-md-0">
                <label for="timezone" class="form-label mb-1">{{ __('common.timezone') }}</label>
                <select name="timezone" id="timezone"
                        class="form-control select2 @error('timezone') is-invalid @enderror"
                        style="width:100%">
                  @foreach($timezones as $group_name => $group_timezones)
                    <optgroup label="{{ $group_name }}">
                      @foreach($group_timezones as $timezone_id => $timezone_label)
                        <option value="{{ $timezone_id }}" @selected($timezone_id === old('timezone'))>
                          {{ $timezone_label }}
                        </option>
                      @endforeach
                    </optgroup>
                  @endforeach
                </select>
                @error('timezone')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Optional: Transfer Hours --}}
            @if (setting('pilots.allow_transfer_hours') === true)
              <div class="row mt-3">
                <div class="col-md-6">
                  <label for="transfer_time" class="form-label mb-1">{{ __('auth.transferhours') }}</label>
                  <input type="number" name="transfer_time" id="transfer_time"
                         class="form-control @error('transfer_time') is-invalid @enderror"
                         value="{{ old('transfer_time') }}" min="0" step="1">
                  @error('transfer_time')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            @endif

            {{-- Passwords --}}
            <div class="row mt-3">
              <div class="col-md-6">
                <label for="password" class="form-label mb-1">{{ __('auth.password') }}</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                @error('password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mt-3 mt-md-0">
                <label for="password_confirmation" class="form-label mb-1">{{ __('passwords.confirm') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       autocomplete="new-password">
                @error('password_confirmation')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Custom User Fields --}}
            @if($userFields)
              <div class="row mt-3">
                @foreach($userFields as $field)
                  <div class="col-md-6">
                    <label for="field_{{ $field->slug }}" class="form-label mb-1">{{ $field->name }}</label>
                    <input type="text"
                           name="field_{{ $field->slug }}"
                           id="field_{{ $field->slug }}"
                           class="form-control @error('field_'.$field->slug) is-invalid @enderror"
                           value="{{ old('field_'.$field->slug) }}">
                    @error('field_'.$field->slug)
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                @endforeach
              </div>
            @endif

            {{-- hCaptcha --}}
            @if($captcha['enabled'] === true)
              <div class="mt-3">
                <label class="form-label mb-2">{{ __('auth.fillcaptcha') }}</label>
                <div class="card-glass p-3">
                  <div class="h-captcha" data-sitekey="{{ $captcha['site_key'] }}"></div>
                </div>
                @if ($errors->has('h-captcha-response'))
                  <div class="invalid-feedback d-block">{{ $errors->first('h-captcha-response') }}</div>
                @endif
              </div>
            @endif

            {{-- Invite (hidden) --}}
            @if($invite)
              <input type="hidden" name="invite" value="{{ $invite->id }}" />
              <input type="hidden" name="invite_token" value="{{ base64_encode($invite->token) }}" />
            @endif

            {{-- Terms / Opt-in --}}
            <div class="card-glass p-3 mt-4">
              @include('auth.toc')
              <div class="d-flex align-items-start mt-2">
                <div class="mr-2">
                  <input type="checkbox" name="toc_accepted" id="toc_accepted" class="form-check-input">
                </div>
                <label for="toc_accepted" class="mb-0">{{ __('auth.tocaccept') }}</label>
              </div>
              @error('toc_accepted')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror

              <div class="d-flex align-items-start mt-2">
                <div class="mr-2">
                  <input type="hidden" name="opt_in" value="0"/>
                  <input type="checkbox" name="opt_in" id="opt_in" value="1" class="form-check-input">
                </div>
                <label for="opt_in" class="mb-0">{{ __('profile.opt-in-descrip') }}</label>
              </div>
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary rounded-pill px-4" id="register_button" disabled>
                {{ __('auth.register') }}
              </button>
            </div>

          </form>
        </div>
      </div>

      {{-- Helper links --}}
      <div class="d-flex justify-content-between mt-3">
        <a href="{{ url('/login') }}" class="btn btn-link p-0">{{ __('common.login') }}</a>
        <a href="{{ url('/password/reset') }}" class="btn btn-link p-0">{{ __('auth.forgotpassword') }}?</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  @if ($captcha['enabled'])
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
  @endif

  <script>
    // Enable register button when ToC accepted
    (function(){
      var cb = document.getElementById('toc_accepted');
      var btn = document.getElementById('register_button');
      if(cb && btn){
        cb.addEventListener('change', function(){
          if (cb.checked) { btn.removeAttribute('disabled'); }
          else { btn.setAttribute('disabled', 'true'); }
        });
      }
    })();
  </script>

  @include('scripts.airport_search') {{-- keeps your Select2/airport setup --}}
@endsection
