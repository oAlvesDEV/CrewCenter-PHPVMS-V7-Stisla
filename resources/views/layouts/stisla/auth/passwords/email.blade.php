@extends('app')
@section('title', __('auth.forgotpassword'))

@section('content')
<div class="container-fluid px-0">

  {{-- HERO --}}
  <div class="dash-hero p-4 p-md-5 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="chip">🔑 {{ __('auth.forgotpassword') }}</span>
          <span class="chip">📧 {{ __('common.email') }}</span>
        </div>
        <h1 class="mb-1">{{ __('auth.forgotpassword') }}</h1>
        <div class="text-muted">
          {{ __('Enter your email below and we’ll send you a password reset link.') }}
        </div>
      </div>
    </div>
  </div>

  {{-- FORM --}}
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-7 col-md-8">
      <div class="card-glass p-0">

        <div class="header-bar">
          📩 Reset Password
        </div>

        <div class="p-4">

          {{-- Success status --}}
          @if (session('status'))
            <div class="alert alert-success mb-3">
              {{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ url('/password/email') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label">{{ __('common.email') }}</label>
              <input id="email"
                     type="email"
                     class="form-control @error('email') is-invalid @enderror"
                     name="email"
                     value="{{ old('email') }}"
                     required autofocus>

              @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary rounded-pill px-4">
                {{ __('auth.sendresetlink') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- Back to login --}}
      <div class="text-center mt-3">
        <a href="{{ url('/login') }}" class="btn btn-link p-0">
          ← {{ __('common.login') }}
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
