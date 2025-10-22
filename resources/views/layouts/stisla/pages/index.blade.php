@extends('app') <!-- layout base do Stisla -->
@section('title', $page->name)

@section('content')
<div class="section">
  <div class="section-header">
    <h1>{{ $page->name }}</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            {!! $page->body !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
