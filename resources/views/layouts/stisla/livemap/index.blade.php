@extends('app')
@section('title', __('common.livemap'))

@section('content')
<div class="section">
    <div class="section-header">
        <h1>{{ __('common.livemap') }}</h1>
    </div>
            <div class="card-body p-0">
                {{ Widget::liveMap() }}
            </div>
        </div>
    </div>
</div>
@endsection
