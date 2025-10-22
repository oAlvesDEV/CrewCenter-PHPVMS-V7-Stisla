@extends('app')
@section('title', trans_choice('common.download', 2))

@section('content')
  @include('flash::message')

  <section class="section">
    <div class="section-header">
      <h1>{{ trans_choice('common.download', 2) }}</h1>
    </div>

    @if(!$grouped_files || \count($grouped_files) === 0)
      <div class="section-body">
        <div class="jumbotron text-center">
          @lang('downloads.none')
        </div>
      </div>
    @else
      <div class="section-body">
        @foreach($grouped_files as $group => $files)
          <div class="card mb-4">
            <div class="card-header">
              <h4>{{ $group }}</h4>
            </div>
            <div class="card-body">
              @include('downloads.table', ['files' => $files])
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </section>
@endsection
