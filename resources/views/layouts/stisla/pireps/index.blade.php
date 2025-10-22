@extends('app')
@section('title', trans_choice('common.pirep', 2))

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ trans_choice('common.pirep', 2) }}</h1>
    <div class="section-header-button">
      <a href="{{ route('frontend.pireps.create') }}" class="btn btn-primary">
        ✈️ @lang('pireps.filenewpirep')
      </a>
    </div>
  </div>

  <div class="section-body">
    @include('flash::message')

    <div class="card">
      <div class="card-header">
        <h4>{{ trans_choice('pireps.pilotreport', 2) }}</h4>
      </div>
      <div class="card-body p-0">
        @include('pireps.table')
      </div>
    </div>

    <div class="text-center mt-4">
      {{ $pireps->withQueryString()->links('pagination.default') }}
    </div>
  </div>
</section>
@endsection
