@extends('user.layout')
@section('title', 'Tableau de bord')

@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Tableau de bord</div>
    <div class="page-sub">Bienvenue, {{ Auth::user()->name }}</div>
  </div>

  <div class="year-selector" style="display:flex;gap:8px;align-items:center">
    <form method="GET" action="{{ route('user') }}" id="filter-form">
      <input type="hidden" name="type" value="{{ $selectedType }}">

      <select name="year" onchange="this.form.submit()"
              style="height:34px;padding:0 12px;font-size:13px;font-weight:500;background:var(--s3);border:1px solid var(--border2);border-radius:8px;color:var(--text);cursor:pointer;outline:none;transition:border-color .2s"
              onfocus="this.style.borderColor='var(--blue)'"
              onblur="this.style.borderColor='var(--border2)'">
        @foreach($years as $y)
          <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
      </select>

      <select name="month" onchange="this.form.submit()"
              style="height:34px;padding:0 12px;font-size:13px;font-weight:500;background:var(--s3);border:1px solid var(--border2);border-radius:8px;color:var(--text);cursor:pointer;outline:none;transition:border-color .2s"
              onfocus="this.style.borderColor='var(--blue)'"
              onblur="this.style.borderColor='var(--border2)'">
        <option value="">-- Mois --</option>
        @php
          $moisLabels = ['','Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
        @endphp
        @foreach($availableMonths as $m)
          <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
            {{ $moisLabels[$m] }}
          </option>
        @endforeach
      </select>
    </form>
  </div>
</div>

<div class="type-tabs">
  <a href="{{ route('user', ['year' => $selectedYear, 'type' => 'eau', 'month' => $selectedMonth]) }}">
    <button class="type-tab {{ $selectedType === 'eau' ? 'active' : '' }}">💧 Eau</button>
  </a>
  <a href="{{ route('user', ['year' => $selectedYear, 'type' => 'elec', 'month' => $selectedMonth]) }}">
    <button class="type-tab {{ $selectedType === 'elec' ? 'active' : '' }}">⚡ Électricité</button>
  </a>
  <a href="{{ route('user', ['year' => $selectedYear, 'type' => 'both', 'month' => $selectedMonth]) }}">
    <button class="type-tab {{ $selectedType === 'both' ? 'active' : '' }}">🔀 Les deux</button>
  </a>
</div>
<div class="metrics" style="{{ $selectedType === 'both' ? 'grid-template-columns:repeat(6,1fr)' : 'grid-template-columns:repeat(4,1fr)' }}">

  @if($selectedType === 'eau' || $selectedType === 'both')
  <div class="mc cyan">
    <div class="mc-label">Conso. eau {{ $selectedYear }}</div>
    <div class="mc-val cyan">{{ $totalEau }} m³</div>
    <div class="mc-trend">Cumul annuel</div>
  </div>
  @endif

  @if($selectedType === 'elec' || $selectedType === 'both')
  <div class="mc blue">
    <div class="mc-label">Conso. élec {{ $selectedYear }}</div>
    <div class="mc-val blue">{{ $totalElec }} kWh</div>
    <div class="mc-trend">Cumul annuel</div>
  </div>
  @endif

  <div class="mc amber">
    <div class="mc-label">Moyenne mensuelle</div>
    <div class="mc-val amber">
      @if($selectedType === 'eau')
        {{ $avgEau }} m³
      @elseif($selectedType === 'elec')
        {{ $avgElec }} kWh
      @else
        {{ $avgEau }} m³ / {{ $avgElec }} kWh
      @endif
    </div>
    <div class="mc-trend">Sur {{ $monthCount }} mois</div>
  </div>

  <div class="mc green">
    <div class="mc-label">Dernier relevé</div>
    <div class="mc-val green">{{ $lastMonth }}</div>
    <div class="mc-trend">Mois le plus récent</div>
  </div>
  @if($selectedMonth && $monthDetail)
  @if(($selectedType === 'eau' || $selectedType === 'both') && $monthDetail['eauFacture'])
  <div class="mc cyan">
    <div class="mc-label">mantant eau — {{ $moisLabels[$selectedMonth] ?? '' }}</div>
    <div class="mc-val cyan">{{ number_format($monthDetail['eauFacture']->montant_ttc, 2) }} DH</div>
    <div class="mc-trend">
      {{ $monthDetail['eauFacture']->consommation }} m³ &nbsp;·&nbsp;
      @php $s = $monthDetail['eauFacture']->status; @endphp
      <span style="color:{{ $s === 'payee' ? '#4ade80' : ($s === 'en_retard' ? '#f87171' : '#fbbf24') }}">
        {{ $s === 'payee' ? 'Payée' : ($s === 'en_retard' ? 'En retard' : 'Impayée') }}
      </span>
    </div>
  </div>
  @endif

  @if(($selectedType === 'elec' || $selectedType === 'both') && $monthDetail['elecFacture'])
  <div class="mc blue">
    <div class="mc-label">montant élec — {{ $moisLabels[$selectedMonth] ?? '' }}</div>
    <div class="mc-val blue">{{ number_format($monthDetail['elecFacture']->montant_ttc, 2) }} DH</div>
    <div class="mc-trend">
      {{ $monthDetail['elecFacture']->consommation }} kWh &nbsp;·&nbsp;
      @php $s = $monthDetail['elecFacture']->status; @endphp
      <span style="color:{{ $s === 'payee' ? '#4ade80' : ($s === 'en_retard' ? '#f87171' : '#fbbf24') }}">
        {{ $s === 'payee' ? 'Payée' : ($s === 'en_retard' ? 'En retard' : 'Impayée') }}
      </span>
    </div>
  </div>
  @endif
@endif

</div>

<div class="chart-card">
  <div class="cc-head">
    <div class="cc-title">Consommation mensuelle — {{ $selectedYear }}</div>
    <div class="cc-legend">
      @if($selectedType !== 'elec')
        <div class="cc-leg-item">
          <div class="cc-dot" style="background:#00cfff"></div>
          Eau (m³)
        </div>
      @endif
      @if($selectedType !== 'eau')
        <div class="cc-leg-item">
          <div class="cc-dot" style="background:#6699ff"></div>
          Élec (kWh)
        </div>
      @endif
    </div>
  </div>
  <div class="chart-wrap">
    <canvas id="mainChart"></canvas>
  </div>
</div>



@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const MONTHS = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];

const eauData  = @json($eauByMonth);
const elecData = @json($elecByMonth);
const type     = "{{ $selectedType }}";

const datasets = [];

if(type !== 'elec') {
  datasets.push({
    label:'Eau (m³)',
    data:eauData,
    borderColor:'#00cfff',
    backgroundColor:'rgba(0,207,255,0.12)',
    tension:.4,
    fill:true
  });
}

if(type !== 'eau') {
  datasets.push({
    label:'Élec (kWh)',
    data:elecData,
    borderColor:'#6699ff',
    backgroundColor:'rgba(61,127,255,0.1)',
    tension:.4,
    fill:true
  });
}

new Chart(document.getElementById('mainChart'), {
  type:'line',
  data:{ labels:MONTHS, datasets },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    plugins:{
      legend:{ display:false }
    },
    scales:{
      x:{ grid:{ color:'rgba(255,255,255,0.05)' }, ticks:{ color:'#7d8590' } },
      y:{ grid:{ color:'rgba(255,255,255,0.05)' }, ticks:{ color:'#7d8590' } }
    }
  }
});
</script>
@endpush