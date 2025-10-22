@extends('app')
@section('title', trans_choice('common.pilot', 2))

@section('content')
<div class="section">
    <div class="section-header">
        <h1>{{ trans_choice('common.pilot', 2) }}</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{ trans_choice('common.pilot', 2) }} List</h4>
            </div>
            <div class="card-body p-0">
                @include('users.table')
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->withQueryString()->links('pagination.default') }}
        </div>
    </div>
</div>
@endsection
