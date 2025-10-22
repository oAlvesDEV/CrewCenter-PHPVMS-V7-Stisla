@php
  if (!function_exists('format_money_any')) {
    function format_money_any($val) {
      if (is_object($val)) {
        if (method_exists($val, 'formatted'))  return $val->formatted();
        if (method_exists($val, 'format'))     return $val->format();
        if (method_exists($val, '__toString')) return (string) $val;
        if (method_exists($val, 'getAmount'))  return number_format((float) $val->getAmount(), 2);
        if (property_exists($val, 'amount'))   return number_format((float) $val->amount, 2);
        return e(json_encode($val));
      }
      return is_null($val) ? '—' : number_format((float) $val, 2);
    }
  }

  $totals = $totals ?? [];
  $pilots   = (int) ($totals['pilots']  ?? 0);
  $flights  = (int) ($totals['flights'] ?? 0);
  $hours    = (int) ($totals['hours']   ?? 0);
  $balance  = $totals['balance'] ?? null;
@endphp

<div class="row g-4">
  <div class="col-6 col-md-3">
    <div class="tile tile-bg-1 p-3 h-100 d-flex flex-column">
      <div class="text-muted small mb-1">👥 {{ __('Pilots') }}</div>
      <div class="stat-number fs-3">{{ number_format($pilots) }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="tile tile-bg-2 p-3 h-100 d-flex flex-column">
      <div class="text-muted small mb-1">✈️ {{ trans_choice('common.flight', 2) }}</div>
      <div class="stat-number fs-3">{{ number_format($flights) }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="tile tile-bg-3 p-3 h-100 d-flex flex-column">
      <div class="text-muted small mb-1">⏱️ {{ __('Hours') }}</div>
      <div class="stat-number fs-3">{{ number_format($hours) }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="tile tile-bg-4 p-3 h-100 d-flex flex-column">
      <div class="text-muted small mb-1">💳 {{ __('Balance') }}</div>
      <div class="stat-number fs-4">{{ format_money_any($balance) }}</div>
    </div>
  </div>
</div>
