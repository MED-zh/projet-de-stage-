@extends('admin.layout')
@section('title', 'Dashboard Admin')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Vue globale</div>
    <div class="page-sub">
      Consommation {{ $selectedSecteur ? 'secteur '.$selectedSecteur : 'totale' }} — {{ now()->year }}
    </div>
  </div>


<form method="GET" action="{{ route('admin.dashboard') }}" style="display:flex;gap:8px;align-items:center">

    {{-- Secteur --}}
    <select name="secteur" onchange="this.form.submit()"
            style="height:34px;padding:0 12px;font-size:13px;font-weight:500;background:var(--s3);border:1px solid var(--border2);border-radius:8px;color:var(--text);cursor:pointer;outline:none">
        <option value="">Tous les secteurs</option>
        @foreach($secteurs as $s)
            <option value="{{ $s }}" {{ $s == $selectedSecteur ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>

    {{-- Contrat (only shown when a secteur is selected) --}}
    @if($selectedSecteur)
        <select name="contrat" onchange="this.form.submit()"
                style="height:34px;padding:0 12px;font-size:13px;font-weight:500;background:var(--s3);border:1px solid var(--border2);border-radius:8px;color:var(--text);cursor:pointer;outline:none">
            <option value="">Tous les contrats</option>
            @foreach($contrats as $c)
                <option value="{{ $c }}" {{ $c == $selectedContrat ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
    @endif

</form>
</div>

<div class="metrics">
  <div class="mc cyan">
    <div class="mc-label">Total eau {{ now()->year }}</div>
    <div class="mc-val cyan">{{ number_format($totalEau, 0, ',', ' ') }} m³</div>
    <div class="mc-trend">Tous contrats confondus</div>
  </div>
  <div class="mc blue">
    <div class="mc-label">Total électricité {{ now()->year }}</div>
    <div class="mc-val blue">{{ number_format($totalElec, 0, ',', ' ') }} kWh</div>
    <div class="mc-trend">Tous contrats confondus</div>
  </div>
  <div class="mc green">
    <div class="mc-label">Contrats actifs</div>
    <div class="mc-val green">{{ $totalContrats }}</div>
    <div class="mc-trend">{{ $totalUsers }} clients enregistrés</div>
  </div>
  <div class="mc amber">
    <div class="mc-label">Techniciens</div>
    <div class="mc-val amber">{{ $totalTechniciens }}</div>
    <div class="mc-trend">Agents sur le terrain</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
  <div class="chart-card">
    <div class="cc-head">
      <div class="cc-title">Eau — mensuel {{ now()->year }}</div>
      <div class="cc-legend">
        <div class="cc-leg-item"><div class="cc-dot" style="background:#00cfff"></div>m³</div>
      </div>
    </div>
    <div class="chart-wrap"><canvas id="eauChart"></canvas></div>
  </div>
  <div class="chart-card">
    <div class="cc-head">
      <div class="cc-title">Électricité — mensuel {{ now()->year }}</div>
      <div class="cc-legend">
        <div class="cc-leg-item"><div class="cc-dot" style="background:#6699ff"></div>kWh</div>
      </div>
    </div>
    <div class="chart-wrap"><canvas id="elecChart"></canvas></div>
  </div>
</div>

<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    
    <!-- Bloc Eau -->
    <div class="tbl-card">
        <div class="tbl-head">
            <div class="cc-title">Top 5 consommateurs Eau</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>N° Contrat</th>
                    <th>Total (m³)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topConsumers as $i => $c)
                <tr>
                    <td style="color:var(--muted2)">{{ $i + 1 }}</td>
                    <td><span class="badge blue">{{ $c->contrat_num }}</span></td>
                    <td style="color:var(--cyan);font-family:var(--display)">{{ number_format($c->total, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Bloc Électricité -->
    <div class="tbl-card">
        <div class="tbl-head">
            <div class="cc-title">Top 5 consommateurs Électricité</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>N° Contrat</th>
                    <th>Total (kWh)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topConsumersElec as $i => $c)
                <tr>
                    <td style="color:var(--muted2)">{{ $i + 1 }}</td>
                    <td><span class="badge blue">{{ $c->contrat_num }}</span></td>
                    <td style="color:var(--cyan);font-family:var(--display)">{{ number_format($c->total, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const MONTHS = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
const opts = (color) => ({
  responsive:true, maintainAspectRatio:false,
  plugins:{legend:{display:false}},
  scales:{
    x:{grid:{color:'rgba(61,127,255,0.06)'},ticks:{color:'rgba(200,210,255,0.4)',font:{size:10}}},
    y:{grid:{color:'rgba(61,127,255,0.06)'},ticks:{color:'rgba(200,210,255,0.4)',font:{size:10}}}
  }
});

const eauRaw  = @json($eauByMonth);
const elecRaw = @json($elecByMonth);

const eauArr  = Array.from({length:12}, (_,i) => eauRaw.find(r=>r.month==i+1)?.total ?? null);
const elecArr = Array.from({length:12}, (_,i) => elecRaw.find(r=>r.month==i+1)?.total ?? null);

new Chart(document.getElementById('eauChart'), {
  type:'line',
  data:{ labels:MONTHS, datasets:[{ data:eauArr, borderColor:'#00cfff', backgroundColor:'rgba(0,207,255,0.1)', tension:.4, fill:true }]},
  options: opts('#00cfff')
});
new Chart(document.getElementById('elecChart'), {
  type:'line',
  data:{ labels:MONTHS, datasets:[{ data:elecArr, borderColor:'#6699ff', backgroundColor:'rgba(61,127,255,0.1)', tension:.4, fill:true }]},
  options: opts('#6699ff')
});
</script>
@endpush