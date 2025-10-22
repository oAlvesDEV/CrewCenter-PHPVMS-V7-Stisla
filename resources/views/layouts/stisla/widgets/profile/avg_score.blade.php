@php
  /**
   * Props (all optional except $user):
   * - $user      : \App\Models\User (required)
   * - $title     : string            (default: "Average Scores & Landing Rate")
   * - $limit     : int               (default: 40 most recent accepted PIREPs)
   * - $chartId   : string            (default: "avgScoreChart-{$user->id}")
   * - $height    : int(px)           (default: 240)
   * - $class     : string            (wrapper col classes; default fits 2x2 grid)
   *
   * Builds points: [{ label: 'YYYY-MM-DD', score: float, rate: float|null }]
   */

  $title   = $title  ?? __('Average Scores & Landing Rate');
  $limit   = $limit  ?? 40;
  $chartId = $chartId ?? ('avgScoreChart-'.$user->id);
  $height  = $height ?? 320;
  $class   = $class  ?? 'col-12 col-md-6';

  // Build points if not provided.
  if (!isset($points)) {
    $points = $user->pireps()
      ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
      ->whereNotNull('score')                        // need a score for the score line
      ->orderBy('created_at', 'asc')                // oldest->newest
      ->get(['score', 'landing_rate', 'created_at'])
      ->take(-1 * $limit)                           // last $limit while preserving order
      ->map(function($p) {
        return [
          'label' => $p->created_at->format('Y-m-d'),
          'score' => (float) $p->score,
          // Some PIREPs may not have a landing rate; keep null to skip in MA
          'rate'  => filled($p->landing_rate) ? (float) $p->landing_rate : null,
        ];
      })
      ->values()
      ->all();
  }

  $hasData = !empty($points);

  // Quick summary chip (last 7 non-null landing rates avg)
  $recentRates = collect($points)->pluck('rate')->filter(fn($v) => $v !== null)->values();
  $recent7Avg  = $recentRates->take(-7)->avg();
  $recent7Text = is_null($recent7Avg) ? '—' : number_format($recent7Avg, 0).' fpm';
@endphp

<div class="card-glass mb-3">
  <div class="card card-glass shadow-sm rounded-3 h-100">
    <div class="header-bar d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <span class="chip"><i class="fas fa-chart-line"></i>&nbsp;{{ $title }}</span>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="chip">📊 {{ count($points) }} pts</span>
        <span class="chip">🛬 {{ __('Avg Ldg (7):') }} {{ $recent7Text }}</span>
      </div>
    </div>

    <div class="card-body p-3">
      @if(!$hasData)
        <div class="d-flex align-items-center justify-content-center text-muted" style="height: {{ $height }}px;">
          {{ __('No accepted PIREPs with scores to chart yet.') }}
        </div>
      @else
        <div style="position:relative;height:{{ $height }}px;width:100%;">
          <canvas id="{{ $chartId }}"></canvas>
        </div>
      @endif
    </div>
  </div>
</div>

@if($hasData)
  @once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
  @endonce

  <script>
    (function initAvgScoreChart() {
      const whenReady = () => {
        try {
          const rawPoints = @json($points);

          const labels = rawPoints.map(p => p.label);
          const scoreVals = rawPoints.map(p => Number(p.score ?? 0));
          const ldgVals   = rawPoints.map(p => (p.rate === null || p.rate === undefined) ? null : Number(p.rate));

          // Moving average helpers (7-flight window)
          const windowSize = 7;

          const movingAvg = (arr, win) => {
            return arr.map((_, idx) => {
              const start = Math.max(0, idx - win + 1);
              const slice = arr.slice(start, idx + 1).filter(v => typeof v === 'number' && !isNaN(v));
              if (!slice.length) return null;
              const avg = slice.reduce((a, b) => a + b, 0) / slice.length;
              return Math.round((avg + Number.EPSILON) * 100) / 100;
            });
          };

          const scoreMA = movingAvg(scoreVals, windowSize);
          const ldgMA   = movingAvg(ldgVals, windowSize); // may include nulls (gaps)

          // Y ranges
          const scoreAll = [...scoreVals, ...scoreMA.filter(v => v !== null)];
          const sMin = Math.min(...scoreAll);
          const sMax = Math.max(...scoreAll);
          const sPad = Math.max(5, Math.round((sMax - sMin) * 0.1));
          const sSuggestedMin = Math.max(0, Math.floor(sMin - sPad));
          const sSuggestedMax = Math.ceil(sMax + sPad);

          // Landing rate (typically negative). Build range from non-null values.
          const lAll = ldgMA.filter(v => v !== null);
          const lMin = lAll.length ? Math.min(...lAll) : -100; // fallback
          const lMax = lAll.length ? Math.max(...lAll) :   0;  // fallback
          const lPad = Math.max(50, Math.round((lMax - lMin) * 0.15));
          const lSuggestedMin = Math.floor(lMin - lPad); // more room below
          const lSuggestedMax = Math.ceil(lMax + lPad);  // likely near/below 0

          const ctx = document.getElementById('{{ $chartId }}');
          if (!ctx) return;

          const chart = new Chart(ctx, {
            type: 'line',
            data: {
              labels,
              datasets: [
                // Scores (pts) – primary Y
                {
                  label: '{{ __("Score") }}',
                  data: scoreVals,
                  tension: 0.35,
                  borderWidth: 2,
                  pointRadius: 2.5,
                  pointHoverRadius: 4,
                  fill: true,
                  borderColor: 'rgba(13,110,253,0.9)',
                  backgroundColor: (c) => {
                    const {chartArea, ctx} = c.chart;
                    if (!chartArea) return 'rgba(13,110,253,0.10)';
                    const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    g.addColorStop(0, 'rgba(13,110,253,0.20)');
                    g.addColorStop(1, 'rgba(13,110,253,0.00)');
                    return g;
                  },
                  yAxisID: 'y',
                  order: 2
                },
                {
                  label: '{{ __("7-Flight Avg (Score)") }}',
                  data: scoreMA,
                  tension: 0.35,
                  borderWidth: 2,
                  pointRadius: 0,
                  borderDash: [6,4],
                  fill: false,
                  borderColor: 'rgba(111,66,193,0.9)',
                  yAxisID: 'y',
                  order: 1
                },
                // Landing rate MA (fpm) – secondary Y
                {
                  label: '{{ __("7-Flight Avg (Landing fpm)") }}',
                  data: ldgMA,
                  tension: 0.35,
                  borderWidth: 2,
                  pointRadius: 0,
                  fill: false,
                  borderColor: 'rgba(25,135,84,0.95)', // Bootstrap success-ish
                  yAxisID: 'y2',
                  order: 0
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              interaction: { mode: 'index', intersect: false },
              plugins: {
                legend: {
                  display: true,
                  labels: { boxWidth: 12, usePointStyle: true }
                },
                tooltip: {
                  enabled: true,
                  callbacks: {
                    title: (items) => items?.[0]?.label ?? '',
                    label: (ctx) => {
                      const isY2 = ctx.dataset.yAxisID === 'y2';
                      const v = ctx.parsed.y;
                      if (v === null || v === undefined) return null;
                      return isY2
                        ? `${ctx.dataset.label}: ${Math.round(v)} fpm`
                        : `${ctx.dataset.label}: ${Number(v).toFixed(1)}`;
                    }
                  }
                }
              },
              scales: {
                x: {
                  grid: { display: false },
                  ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 }
                },
                y: { // Scores
                  position: 'left',
                  suggestedMin: sSuggestedMin,
                  suggestedMax: sSuggestedMax,
                  grid: { color: 'rgba(0,0,0,0.06)' },
                  ticks: { stepSize: Math.max(5, Math.round((sSuggestedMax - sSuggestedMin)/5)) },
                  title: { display: true, text: '{{ __("Score (pts)") }}' }
                },
                y2: { // Landing rate
                  position: 'right',
                  suggestedMin: lSuggestedMin,
                  suggestedMax: lSuggestedMax,
                  grid: { drawOnChartArea: false },
                  ticks: {
                    callback: (v) => `${v} fpm`
                  },
                  title: { display: true, text: '{{ __("Landing Rate (fpm)") }}' }
                }
              }
            }
          });

          const ro = new ResizeObserver(() => chart.resize());
          ro.observe(ctx.parentElement);
        } catch (e) {
          console.error('Avg Score & Landing chart init error:', e);
        }
      };

      if (window.Chart) whenReady();
      else window.addEventListener('load', whenReady);
    })();
  </script>
@endif
