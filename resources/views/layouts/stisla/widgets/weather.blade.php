{{-- WEATHER CARD — Dashboard Look --}}
<div class="card-glass mb-3">
  <div class="header-bar">
    🌤️ @lang('dashboard.weatherat', ['ICAO' => $icao ?? ($metar['icao'] ?? '')])
  </div>

  @php
    // Map CheckWX flight categories to badge colors
    $catRaw = isset($metar['category']) ? strtoupper($metar['category']) : null;
    $wxBadgeMap = [
      'VFR'  => 'badge-success',
      'MVFR' => 'badge-info',
      'IFR'  => 'badge-warning',
      'LIFR' => 'badge-danger',
    ];
    $wxBadgeClass = $catRaw ? ($wxBadgeMap[$catRaw] ?? 'badge-secondary') : 'badge-secondary';

    // convenience labels
    $distUnit = $unit_dist ?? 'sm';
    $altUnit  = strtoupper($unit_alt ?? 'ft');
    $tmpUnit  = strtoupper($unit_temp ?? 'c');
  @endphp

  <div class="table-responsive">
    <table class="table table-hover table-dva mb-0">
      <tbody>

      @if($config['raw_only'] != true && $metar)
        <tr>
          <th class="text-nowrap">@lang('widgets.weather.conditions')</th>
          <td>
            @if($catRaw)
              <span class="badge {{ $wxBadgeClass }} mr-2">{{ $catRaw }}</span>
            @endif
            {{ $metar['flight_rules'] ?? '' }}
          </td>
        </tr>

        <tr>
          <th class="text-nowrap">@lang('widgets.weather.wind')</th>
          <td>
            @if($metar['wind_speed'] < '1')
              {{ __('Calm') }}
            @else
              {{ $metar['wind_speed'] }} kts @lang('common.from')
              {{ $metar['wind_direction_label'] }} ({{ $metar['wind_direction'] }}°)
            @endif
            @if(!empty($metar['wind_gust_speed']))
              &nbsp;•&nbsp;@lang('widgets.weather.guststo') {{ $metar['wind_gust_speed'] }} kts
            @endif
          </td>
        </tr>

        @if(!empty($metar['visibility']))
          <tr>
            <th class="text-nowrap">{{ __('Visibility') }}</th>
            <td>{{ $metar['visibility'][$distUnit] ?? '—' }} {{ $distUnit }}</td>
          </tr>
        @endif

        @if(!empty($metar['runways_visual_range']))
          <tr>
            <th class="text-nowrap">{{ __('Runway Visual Range') }}</th>
            <td>
              @foreach($metar['runways_visual_range'] as $rvr)
                <strong>RWY{{ $rvr['runway'] }}</strong>: {{ $rvr['report'] }}<br>
              @endforeach
            </td>
          </tr>
        @endif

        @if(!empty($metar['present_weather_report']) && $metar['present_weather_report'] !== 'Dry')
          <tr>
            <th class="text-nowrap">{{ __('Phenomena') }}</th>
            <td>{{ $metar['present_weather_report'] }}</td>
          </tr>
        @endif

        @if(!empty($metar['clouds']) || !empty($metar['cavok']))
          <tr>
            <th class="text-nowrap">@lang('widgets.weather.clouds')</th>
            <td>
              @if(($unit_alt ?? 'ft') === 'ft')
                {{ $metar['clouds_report_ft'] ?? '—' }}
              @else
                {{ $metar['clouds_report'] ?? '—' }}
              @endif
              @if(!empty($metar['cavok']) && (int)$metar['cavok'] === 1)
                &nbsp;•&nbsp;{{ __('Ceiling and Visibility OK') }}
              @endif
            </td>
          </tr>
        @endif

        @if(!empty($metar['temperature']))
          <tr>
            <th class="text-nowrap">@lang('widgets.weather.temp')</th>
            <td>
              {{ $metar['temperature'][$unit_temp] ?? 0 }} °{{ $tmpUnit }}
              @if(!empty($metar['dew_point']))
                , @lang('widgets.weather.dewpoint') {{ $metar['dew_point'][$unit_temp] ?? 0 }} °{{ $tmpUnit }}
              @endif
              @if(!empty($metar['humidity']))
                , @lang('widgets.weather.humidity') {{ $metar['humidity'] }}%
              @endif
            </td>
          </tr>
        @endif

        @if(!empty($metar['barometer']))
          <tr>
            <th class="text-nowrap">@lang('widgets.weather.barometer')</th>
            <td>
              {{ number_format($metar['barometer']['hPa']) }} hPa
              / {{ number_format($metar['barometer']['inHg'], 2) }} inHg
            </td>
          </tr>
        @endif

        @if(!empty($metar['recent_weather_report']))
          <tr>
            <th class="text-nowrap">{{ __('Recent Phenomena') }}</th>
            <td>{{ $metar['recent_weather_report'] }}</td>
          </tr>
        @endif

        @if(!empty($metar['runways_report']))
          <tr>
            <th class="text-nowrap">{{ __('Runway Condition') }}</th>
            <td>
              @foreach($metar['runways_report'] as $runway)
                <strong>RWY{{ $runway['runway'] }}</strong>: {{ $runway['report'] }}<br>
              @endforeach
            </td>
          </tr>
        @endif

        @if(!empty($metar['remarks']))
          <tr>
            <th class="text-nowrap">@lang('widgets.weather.remarks')</th>
            <td>{{ $metar['remarks'] }}</td>
          </tr>
        @endif

        <tr>
          <th class="text-nowrap">@lang('widgets.weather.updated')</th>
          <td>{{ $metar['observed_time'] }} ({{ $metar['observed_age'] }})</td>
        </tr>
      @endif

      {{-- Always show raw strings at bottom --}}
      <tr>
        <th class="text-nowrap">@lang('common.metar')</th>
        <td>@if($metar) <code class="small d-block">{{ $metar['raw'] }}</code> @else @lang('widgets.weather.nometar') @endif</td>
      </tr>
      <tr>
        <th class="text-nowrap">TAF</th>
        <td>@if($taf) <code class="small d-block">{{ $taf['raw'] }}</code> @else @lang('widgets.weather.nometar') @endif</td>
      </tr>

      </tbody>
    </table>
  </div>
</div>
