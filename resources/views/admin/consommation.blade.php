@extends('admin.layout')
@section('title', 'Consommation globale')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Consommation globale</div>
    <div class="page-sub">
      {{ $selectedSecteur ? 'Secteur '.$selectedSecteur : 'Tous les secteurs' }} — {{ $selectedYear }}
    </div>
  </div>


<div style="display:flex;gap:8px;align-items:center">
    <form method="GET" action="{{ route('admin.consommation') }}" id="filter-form">
        <input type="hidden" name="type" value="{{ $selectedType }}">

        {{-- Année --}}
        <select name="year" onchange="this.form.submit()"
                style="height:34px;padding:0 12px;font-size:13px;font-weight:500;background:var(--s3);border:1px solid var(--border2);border-radius:8px;color:var(--text);cursor:pointer;outline:none">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>

        {{-- Secteur --}}
        <select name="secteur" onchange="this.form.submit()"
                style="margin-top:8px;height:34px;padding:0 12px;font-size:13px;font-weight:500;background:var(--s3);border:1px solid var(--border2);border-radius:8px;color:var(--text);cursor:pointer;outline:none">
            <option value="">Tous les secteurs</option>
            @foreach($secteurs as $s)
                <option value="{{ $s }}" {{ $s == $selectedSecteur ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </form>
</div>
</div>

<div class="type-tabs">
  <a href="{{ route('admin.consommation', ['year' => $selectedYear, 'type' => 'eau', 'secteur' => $selectedSecteur]) }}">
    <button class="type-tab {{ $selectedType === 'eau' ? 'active' : '' }}">💧 Eau</button>
  </a>
  <a href="{{ route('admin.consommation', ['year' => $selectedYear, 'type' => 'elec', 'secteur' => $selectedSecteur]) }}">
    <button class="type-tab {{ $selectedType === 'elec' ? 'active' : '' }}">⚡ Électricité</button>
  </a>
  <a href="{{ route('admin.consommation', ['year' => $selectedYear, 'type' => 'both', 'secteur' => $selectedSecteur]) }}">
    <button class="type-tab {{ $selectedType === 'both' ? 'active' : '' }}">🔀 Les deux</button>
  </a>
</div>

<div class="chart-card">
  <div class="cc-head">
    <div class="cc-title">Consommation mensuelle — {{ $selectedYear }}</div>
    <div class="cc-legend">
      @if($selectedType !== 'elec')
        <div class="cc-leg-item"><div class="cc-dot" style="background:#00cfff"></div>Eau (m³)</div>
      @endif
      @if($selectedType !== 'eau')
        <div class="cc-leg-item"><div class="cc-dot" style="background:#6699ff"></div>Élec (kWh)</div>
      @endif
    </div>
  </div>
  <div class="chart-wrap" style="height:280px"><canvas id="mainChart"></canvas></div>
</div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const MONTHS = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
const eauData  = @json(array_values($eauByMonth));
const elecData = @json(array_values($elecByMonth));
const type     = "{{ $selectedType }}";
const datasets = [];
if(type !== 'elec') datasets.push({ label:'Eau (m³)',   data:eauData,  borderColor:'#00cfff', backgroundColor:'rgba(0,207,255,0.12)', tension:.4, fill:true });
if(type !== 'eau')  datasets.push({ label:'Élec (kWh)', data:elecData, borderColor:'#6699ff', backgroundColor:'rgba(61,127,255,0.1)',  tension:.4, fill:true });
new Chart(document.getElementById('mainChart'), {
  type:'line',
  data:{ labels:MONTHS, datasets },
  options:{
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{
      x:{grid:{color:'rgba(61,127,255,0.06)'},ticks:{color:'rgba(200,210,255,0.4)',font:{size:10}}},
      y:{grid:{color:'rgba(61,127,255,0.06)'},ticks:{color:'rgba(200,210,255,0.4)',font:{size:10}}}
    }
  }
});
</script>
@endpush