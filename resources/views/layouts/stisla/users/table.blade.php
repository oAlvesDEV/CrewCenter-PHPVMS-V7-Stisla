@extends('app')
@section('title', __('common.pilot').' '.__('common.list'))

@section('content')
<div class="section">
  {{-- Header --}}
  <div class="section-header d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
      <h1 class="h3 mb-1"><i class="fas fa-user-tie text-primary"></i> {{ __('Pilots') }}</h1>
      <p class="text-muted mb-0">{{ __('Browse all active pilots in the airline') }}</p>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ url()->current() }}" class="form-inline mt-3 mt-md-0" role="search">
      @foreach(request()->except('q', 'page') as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endforeach
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="{{ __('Search by name, ident or airport…') }}" value="{{ request('q', '') }}">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
          @if(request()->filled('q'))
            <a class="btn btn-light border" href="{{ url()->current() }}" title="{{ __('Clear') }}">
              <i class="fas fa-times"></i>
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>

  {{-- Card Table --}}
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h6 class="mb-0 text-primary font-weight-bold">
        <i class="fas fa-plane"></i> {{ __('Active Pilots') }}
      </h6>
      <small class="text-muted">
        @php
          $from = ($users->currentPage() - 1) * $users->perPage() + 1;
          $to   = min($users->total(), $users->currentPage() * $users->perPage());
        @endphp
        {{ __('Showing') }} {{ $users->total() ? $from : 0 }}–{{ $users->total() ? $to : 0 }} {{ __('of') }} {{ $users->total() }}
      </small>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="bg-light">
            <tr class="text-center text-uppercase small text-muted">
              <th></th>
              <th>@sortablelink('name', __('common.name'))</th>
              <th></th>
              <th>@sortablelink('airline_id', __('common.airline'))</th>
              <th>@sortablelink('curr_airport_id', __('user.location'))</th>
              <th>@sortablelink('flights', trans_choice('common.flight', 2))</th>
              <th>@sortablelink('flight_time', trans_choice('common.hour', 2))</th>
              <th>IVAO</th>
              <th>VATSIM</th>
            </tr>
          </thead>

          <tbody>
            @forelse($users as $user)
              <tr class="text-center">
                {{-- Avatar --}}
                <td>
                  <img class="rounded-circle shadow-sm"
                       src="{{ $user->avatar ? $user->avatar->url : $user->gravatar(256).'&s=256' }}"
                       alt="avatar"
                       style="height:48px;width:48px;object-fit:cover;">
                </td>

                {{-- Name --}}
                <td class="text-left">
                  <a href="{{ route('frontend.users.show.public', [$user->id]) }}" class="font-weight-bold text-dark">
                    {{ $user->ident }} {{ $user->name_private }}
                  </a>
                </td>

                {{-- Country --}}
                <td>
                  @if(filled($user->country))
                    <span class="flag-icon flag-icon-{{ $user->country }}" title="{{ $country->alpha2($user->country)['name'] }}"></span>
                  @endif
                </td>

                <td>{{ optional($user->airline)->icao }}</td>
                <td>{{ $user->current_airport ? $user->curr_airport_id : '–' }}</td>
                <td>{{ $user->flights }}</td>
                <td>@minutestotime($user->flight_time)</td>

                {{-- IVAO --}}
                <td>
                  @php $ivao_id = optional($user->fields->firstWhere('name', Theme::getSetting('gen_ivao_field')))->value; @endphp
                  @if($ivao_id)
                    <a href="https://www.ivao.aero/member.aspx?id={{ $ivao_id }}" target="_blank" class="badge badge-light border">
                      {{ $ivao_id }}
                    </a>
                  @else
                    <span class="text-muted">–</span>
                  @endif
                </td>

                {{-- VATSIM --}}
                <td>
                  @php $vatsim_id = optional($user->fields->firstWhere('name', Theme::getSetting('gen_vatsim_field')))->value; @endphp
                  @if($vatsim_id)
                    <a href="https://stats.vatsim.net/search_id.php?id={{ $vatsim_id }}" target="_blank" class="badge badge-light border">
                      {{ $vatsim_id }}
                    </a>
                  @else
                    <span class="text-muted">–</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                  {{ __('No pilots found for this search.') }}
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center">
      {{-- Per page --}}
      <form method="GET" action="{{ url()->current() }}" class="form-inline">
        @foreach(request()->except('per_page', 'page') as $k => $v)
          <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach
        @php
          $currentPerPage = (int) (request('per_page', $users->perPage()) ?: 25);
          $perPageOptions = [10, 25, 50, 100];
          if (!in_array($currentPerPage, $perPageOptions)) { $perPageOptions[] = $currentPerPage; sort($perPageOptions); }
        @endphp
        <label for="perPage" class="text-muted mr-2">{{ __('Rows per page') }}</label>
        <select id="perPage" name="per_page" class="custom-select custom-select-sm" onchange="this.form.submit()">
          @foreach($perPageOptions as $opt)
            <option value="{{ $opt }}" {{ $currentPerPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
          @endforeach
        </select>
      </form>

      <div class="text-muted small">
        {{ __('Page') }} {{ $users->currentPage() }} {{ __('of') }} {{ $users->lastPage() }}
      </div>

      <div>
        {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection
