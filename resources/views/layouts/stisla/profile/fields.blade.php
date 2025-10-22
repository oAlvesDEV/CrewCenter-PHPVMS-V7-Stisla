{{-- Profile Edit — DVA Glass Form --}}
<div class="card-glass mb-3">
  <div class="header-bar">👤 {{ __('common.profile') }}</div>

  <div class="p-3">
    {{-- Identity --}}
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="name" class="mb-1">{{ __('common.name') }}</label>
        <input type="text"
               name="name" id="name"
               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{ old('name', $user->name) }}">
        @if ($errors->has('name')) <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div> @endif
      </div>

      <div class="form-group col-md-6">
        <label for="email" class="mb-1">{{ __('common.email') }}</label>
        <input type="email"
               name="email" id="email"
               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email', $user->email) }}">
        @if ($errors->has('email')) <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div> @endif
      </div>
    </div>

    {{-- Location & Company --}}
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="airline_id" class="mb-1">{{ __('common.airline') }}</label>
        <select name="airline_id" id="airline_id"
                class="form-control select2 {{ $errors->has('airline_id') ? 'is-invalid' : '' }}"
                style="width:100%">
          @foreach($airlines as $airline_id => $airline_label)
            <option value="{{ $airline_id }}"
                    @selected(old('airline_id', $user->airline_id) === $airline_id)>{{ $airline_label }}</option>
          @endforeach
        </select>
        @if ($errors->has('airline_id')) <div class="invalid-feedback d-block">{{ $errors->first('airline_id') }}</div> @endif
      </div>

      <div class="form-group col-md-6">
        <label for="home_airport_id" class="mb-1">{{ __('airports.home') }}</label>
        <select name="home_airport_id" id="home_airport_id"
                class="form-control airport_search {{ $hubs_only ? 'hubs_only' : '' }} {{ $errors->has('home_airport_id') ? 'is-invalid' : '' }}"
                style="width:100%">
          @foreach($airports as $airport_id => $airport_label)
            <option value="{{ $airport_id }}"
                    @selected(old('home_airport_id', $user->home_airport_id) === $airport_id)>{{ $airport_label }}</option>
          @endforeach
        </select>
        @if ($errors->has('home_airport_id')) <div class="invalid-feedback d-block">{{ $errors->first('home_airport_id') }}</div> @endif
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="country" class="mb-1">{{ __('common.country') }}</label>
        <select name="country" id="country"
                class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}"
                style="width:100%">
          @foreach($countries as $country_id => $country_label)
            <option value="{{ $country_id }}"
                    @selected(old('country', $user->country) === $country_id)>{{ $country_label }}</option>
          @endforeach
        </select>
        @if ($errors->has('country')) <div class="invalid-feedback d-block">{{ $errors->first('country') }}</div> @endif
      </div>

      <div class="form-group col-md-6">
        <label for="timezone" class="mb-1">{{ __('common.timezone') }}</label>
        <select name="timezone" id="timezone"
                class="form-control select2 {{ $errors->has('timezone') ? 'is-invalid' : '' }}"
                style="width:100%">
          @foreach($timezones as $group_name => $group_timezones)
            <optgroup label="{{ $group_name }}">
              @foreach($group_timezones as $timezone_id => $timezone_label)
                <option value="{{ $timezone_id }}"
                        @selected(old('timezone', $user->timezone) === $timezone_id)>{{ $timezone_label }}</option>
              @endforeach
            </optgroup>
          @endforeach
        </select>
        @if ($errors->has('timezone')) <div class="invalid-feedback d-block">{{ $errors->first('timezone') }}</div> @endif
      </div>
    </div>
  </div>
</div>

{{-- Password --}}
<div class="card-glass mb-3">
  <div class="header-bar">🔒 {{ __('profile.changepassword') }}</div>
  <div class="p-3">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="password" class="mb-1">{{ __('profile.newpassword') }}</label>
        <input type="password"
               name="password" id="password"
               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
        @if ($errors->has('password')) <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div> @endif
      </div>
      <div class="form-group col-md-6">
        <label for="password_confirmation" class="mb-1">{{ __('passwords.confirm') }}</label>
        <input type="password"
               name="password_confirmation" id="password_confirmation"
               class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
        @if ($errors->has('password_confirmation')) <div class="invalid-feedback d-block">{{ $errors->first('password_confirmation') }}</div> @endif
      </div>
    </div>
  </div>
</div>

{{-- Avatar & Preferences --}}
<div class="card-glass mb-3">
  <div class="header-bar">🖼️ {{ __('profile.avatar') }} & ⚙️ {{ __('profile.opt-in') }}</div>
  <div class="p-3">
    <div class="form-row align-items-end">
      <div class="form-group col-md-8">
        <label for="avatar" class="mb-1">{{ __('profile.avatar') }}</label>
        <input type="file"
               name="avatar" id="avatar"
               class="form-control-file {{ $errors->has('avatar') ? 'is-invalid' : '' }}">
        <small class="text-muted d-block mt-1">
          {{ __('profile.avatarresize', ['width'=>config('phpvms.avatar.width'),'height'=>config('phpvms.avatar.height')]) }}
        </small>
        @if ($errors->has('avatar')) <div class="invalid-feedback d-block">{{ $errors->first('avatar') }}</div> @endif
      </div>

      <div class="form-group col-md-4">
        <label class="mb-1 d-block">{{ __('profile.opt-in') }}</label>
        <div class="custom-control custom-switch">
          <input type="hidden" name="opt_in" value="0">
          <input type="checkbox"
                 class="custom-control-input"
                 id="opt_in"
                 name="opt_in"
                 value="1"
                 {{ old('opt_in', $user->opt_in) ? 'checked' : '' }}>
          <label class="custom-control-label" for="opt_in">{{ __('profile.opt-in-descrip') }}</label>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Custom Fields --}}
@if($userFields && count($userFields))
  <div class="card-glass mb-3">
    <div class="header-bar">📝 {{ trans_choice('common.field', 2) }}</div>
    <div class="p-3">
      <div class="form-row">
        @foreach($userFields as $field)
          <div class="form-group col-md-6">
            <label for="field_{{ $field->slug }}" class="mb-1">
              {{ $field->name }} @if($field->required) <span class="text-danger">*</span> @endif
            </label>
            <input type="text"
                   name="field_{{ $field->slug }}"
                   id="field_{{ $field->slug }}"
                   class="form-control {{ $errors->has('field_'.$field->slug) ? 'is-invalid' : '' }}"
                   value="{{ old('field_'.$field->slug, $field->value) }}">
            @if ($errors->has('field_'.$field->slug))
              <div class="invalid-feedback d-block">{{ $errors->first('field_'.$field->slug) }}</div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endif

{{-- Submit --}}
<div class="d-flex justify-content-end">
  <button type="submit" class="btn btn-primary rounded-pill px-4">
    @lang('profile.updateprofile')
  </button>
</div>
