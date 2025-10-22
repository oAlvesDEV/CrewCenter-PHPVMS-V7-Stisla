@extends('app')
@section('title', trans_choice('common.flight', 2))

@section('content')
<div class="row">
    @include('flash::message')

    {{-- Lista de Voos --}}
    <div class="col-lg-9 col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="text-primary"><i class="fas fa-plane-departure"></i> {{ trans_choice('common.flight', 2) }}</h4>
            </div>
            <div class="card-body">
                @include('flights.table')
            </div>
        </div>
    </div>

    {{-- Navegação e Busca --}}
    <div class="col-lg-3 col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="fas fa-filter"></i> {{ __('Filters') }}</h5>
            </div>
            <div class="card-body">
                @include('flights.nav')
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-search"></i> {{ __('Search') }}</h5>
            </div>
            <div class="card-body">
                @include('flights.search')
            </div>
        </div>
    </div>
</div>

{{-- Paginação --}}
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                {{ $flights->withQueryString()->links('pagination.default') }}
            </div>
        </div>
    </div>
</div>

{{-- Bids de Aircraft --}}
@if (setting('bids.block_aircraft', false))
    @include('flights.bids_aircraft')
@endif
@endsection

@include('flights.scripts')
