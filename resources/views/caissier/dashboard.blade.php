@extends('caissier.layout')
@section('title', 'Dashboard')
@section('content')

<style>
.kpi-card {
  border: 1px solid var(--border);
  border-radius: var(--r);
  background: var(--s2);
  padding: 1rem 1.1rem;
  transition: border-color .15s;
}
.kpi-card:hover { border-color: rgba(0,207,255,0.2); }
.kpi-label {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--muted2);
  margin-bottom: 8px;
}
.kpi-val {
  font-family: var(--display);
  font-size: 26px;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 6px;
}
.kpi-unit { font-size: 13px; color: var(--muted2); font-weight: 400; }
.kpi-sub  { font-size: 11px; color: var(--muted2); }
.dash-card {
  border: 1px solid var(--border);
  border-radius: var(--r);
  background: var(--s2);
  padding: 1rem 1.1rem;
}
.dash-card-title {
  font-size: 13px;
  font-weight: 600;
  color: var(--white);
  margin-bottom: 14px;
  display: flex;
  align-items: center;
}
.btn-voir-tout {
  font-size: 11px;
  color: var(--cyan);
  font-weight: 400;
  border: 1px solid rgba(0,207,255,0.35);
  border-radius: var(--r);
  padding: 3px 10px;
  text-decoration: none;
  transition: background .15s, border-color .15s;
}
.btn-voir-tout:hover {
  background: rgba(0,207,255,0.07);
  border-color: rgba(0,207,255,0.6);
  color: var(--cyan);
  text-decoration: none;
}
.pmt-row {
  border-bottom: 1px solid rgba(255,255,255,0.04);
  border-left: 3px solid transparent;
  transition: border-left-color .15s, background .15s;
}
.pmt-row:hover {
  border-left-color: var(--green);
  background: rgba(255,255,255,0.03);
}
.tbl-urgentes {
  width: 100%;
  min-width: 860px;
  border-collapse: collapse;
}
.tbl-urgentes thead th {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: .06em;
  color: var(--muted2);
  padding: 10px 12px;
  text-align: left;
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
  background: var(--s3);
  position: sticky;
  top: 0;
  z-index: 5;
}
.tbl-urgentes tbody tr {
  border-bottom: 1px solid rgba(255,255,255,0.04);
  transition: background .12s;
}
.tbl-urgentes tbody tr:hover { background: rgba(255,255,255,0.03); }
.tbl-urgentes tbody td {
  padding: 10px 12px;
  vertical-align: middle;
  white-space: nowrap;
  font-size: 12px;
}
</style>

<div class="page-header">
  <div>
    <div class="page-title">Dashboard</div>
    <div class="page-sub">Vue d'ensemble — {{ now()->translatedFormat('d F Y') }}</div>
  </div>
</div>

{{-- KPI CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">

  <div class="kpi-card">
    <div class="kpi-label">Encaissé aujourd'hui</div>
    <div class="kpi-val" style="color:var(--green)">
      {{ number_format($encaisseAujourdhui, 2) }}
      <span class="kpi-unit">MAD</span>
    </div>
    <div class="kpi-sub">Mes encaissements</div>
  </div>

  <div class="kpi-card">
    <div class="kpi-label">Encaissé ce mois</div>
    <div class="kpi-val" style="color:var(--cyan)">
      {{ number_format($encaisseMois, 2) }}
      <span class="kpi-unit">MAD</span>
    </div>
    <div class="kpi-sub">{{ now()->translatedFormat('F Y') }}</div>
  </div>

  <div class="kpi-card" style="cursor:pointer" onclick="location.href='{{ route('caissier.factures.index') }}'">
    <div class="kpi-label">Factures impayées</div>
    <div class="kpi-val" style="color:var(--text)">{{ $impayees }}</div>
    <div class="kpi-sub">En attente d'encaissement</div>
  </div>

  <div class="kpi-card" style="cursor:pointer;border-color:rgba(248,113,113,0.25)" onclick="location.href='{{ route('caissier.factures.index') }}?status=en_retard'">
    <div class="kpi-label">En retard</div>
    <div class="kpi-val" style="color:#f87171">{{ $enRetard }}</div>
    <div class="kpi-sub">Dépassement d'échéance</div>
  </div>

</div>

{{-- MIDDLE ROW --}}
<div style="display:grid;grid-template-columns:1fr 320px;gap:14px;margin-bottom:20px">

  {{-- Derniers paiements --}}
  <div class="dash-card">
    <div class="dash-card-title">
      Mes derniers encaissements aujourd'hui
      <a href="{{ route('caissier.paiements.index') }}" class="btn-voir-tout" style="margin-left:auto">
        Voir tout →
      </a>
    </div>
    @if($derniersPaiements->isEmpty())
      <div style="padding:32px;text-align:center;color:var(--muted2);font-size:13px">
        Aucun encaissement aujourd'hui
      </div>
    @else
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr>
            <th style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);padding:6px 0;text-align:left;border-bottom:1px solid var(--border)">N° Reçu</th>
            <th style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);padding:6px 0;text-align:left;border-bottom:1px solid var(--border)">Client</th>
            <th style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);padding:6px 8px;text-align:right;border-bottom:1px solid var(--border)">Montant</th>
            <th style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);padding:6px 0;text-align:right;border-bottom:1px solid var(--border)">Heure</th>
          </tr>
        </thead>
        <tbody>
          @foreach($derniersPaiements as $p)
          <tr class="pmt-row">
            <td style="padding:9px 0;font-family:monospace;font-size:11px;color:var(--blue-hi)">
              <a href="{{ route('caissier.paiements.recu', $p->numero_recu) }}" style="color:var(--blue-hi);text-decoration:none">
                {{ $p->numero_recu }}
              </a>
            </td>
            <td style="padding:9px 0;font-size:12px;color:var(--text)">
              {{ $p->contrat?->utilisateur?->name ?? '—' }}
            </td>
            <td style="padding:9px 8px;text-align:right;font-family:monospace;font-size:13px;font-weight:600;color:var(--green)">
              {{ number_format($p->montant_total, 2) }}
              <span style="font-size:10px;color:var(--muted2)">MAD</span>
            </td>
            <td style="padding:9px 0;text-align:right;font-size:11px;color:var(--muted2)">
              {{ $p->created_at->format('H:i') }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  {{-- Répartition --}}
  <div class="dash-card">
    <div class="dash-card-title">Répartition aujourd'hui</div>

    @php
      $eau  = $repartition['eau']          ?? ['count' => 0, 'total' => 0];
      $elec = $repartition['electricite']  ?? ['count' => 0, 'total' => 0];
      $totalJour = $eau['total'] + $elec['total'];
      $pctEau  = $totalJour > 0 ? round($eau['total']  / $totalJour * 100) : 0;
      $pctElec = $totalJour > 0 ? round($elec['total'] / $totalJour * 100) : 0;
    @endphp

    <div style="display:flex;flex-direction:column;gap:14px;margin-top:4px">
      <div>
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px">
          <span>💧 Eau <span style="color:var(--muted2);font-size:11px">({{ $eau['count'] }} fact.)</span></span>
          <span style="font-family:monospace;color:var(--text)">{{ number_format($eau['total'], 2) }} MAD</span>
        </div>
        <div style="height:6px;background:rgba(255,255,255,0.07);border-radius:3px;overflow:hidden">
          <div style="height:100%;width:{{ $pctEau }}%;background:#64b5f6;border-radius:3px;transition:width .4s"></div>
        </div>
      </div>
      <div>
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px">
          <span>⚡ Électricité <span style="color:var(--muted2);font-size:11px">({{ $elec['count'] }} fact.)</span></span>
          <span style="font-family:monospace;color:var(--text)">{{ number_format($elec['total'], 2) }} MAD</span>
        </div>
        <div style="height:6px;background:rgba(255,255,255,0.07);border-radius:3px;overflow:hidden">
          <div style="height:100%;width:{{ $pctElec }}%;background:#ffb74d;border-radius:3px;transition:width .4s"></div>
        </div>
      </div>
      <div style="margin-top:4px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.06);display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:11px;color:var(--muted2);text-transform:uppercase;letter-spacing:.06em">Total jour</span>
        <span style="font-family:monospace;font-size:16px;font-weight:700;color:var(--green)">
          {{ number_format($totalJour, 2) }} MAD
        </span>
      </div>
    </div>
  </div>

</div>

{{-- FACTURES URGENTES --}}
<div class="tbl-card" style="padding:0;overflow:hidden;display:flex;flex-direction:column">

  <div class="tbl-head" style="padding:1rem 1.25rem;flex-shrink:0">
    <div style="font-family:var(--display);font-size:14px;font-weight:700;color:var(--white)">
      Factures urgentes
      <span style="font-size:11px;font-weight:400;color:var(--muted2);margin-left:8px">
        Les 5 plus anciennes
      </span>
    </div>
    <a href="{{ route('caissier.factures.index') }}" class="btn btn-ghost btn-sm btn-voir-tout" style="margin-left:auto">
      Voir toutes →
    </a>
  </div>

  <div style="overflow-y:auto;overflow-x:auto;max-height:300px">
    <table class="tbl-urgentes">
      <thead>
        <tr>
          <th>N° Facture</th>
          <th>Client</th>
          <th>Contrat</th>
          <th>Secteur</th>
          <th>Type</th>
          <th>Échéance</th>
          <th style="text-align:right">Montant TTC</th>
          <th>Statut</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($facturesUrgentes as $f)
        <tr>
          <td style="font-family:monospace;font-size:11px;color:var(--blue-hi)">{{ $f->numero_facture }}</td>
          <td>{{ $f->contrat?->utilisateur?->name ?? '—' }}</td>
          <td style="font-family:monospace;font-size:11px;color:var(--muted)">{{ $f->contrat_num }}</td>
          <td style="color:var(--muted)">{{ $f->contrat?->nom_secteur ?? '—' }}</td>
          <td>
            @if($f->type === 'eau')
              <span class="badge blue">💧 Eau</span>
            @else
              <span class="badge amber">⚡ Électricité</span>
            @endif
          </td>
          <td style="font-weight:600;color:{{ $f->status === 'en_retard' ? '#f87171' : 'var(--text)' }}">
            {{ \Carbon\Carbon::parse($f->date_echeance)->format('d/m/Y') }}
          </td>
          <td style="text-align:right;font-family:monospace;font-weight:700">
            {{ number_format($f->montant_ttc, 2) }}
            <span style="font-size:10px;color:var(--muted2)">MAD</span>
          </td>
          <td>
            @if($f->status === 'en_retard')
              <span class="badge red">En retard</span>
            @else
              <span class="badge gray">Impayée</span>
            @endif
          </td>
          <td>
            <a href="{{ route('caissier.factures.payer', $f) }}" class="btn btn-success btn-sm">
              Encaisser
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="text-align:center;padding:32px;color:var(--muted2)">
            Aucune facture urgente 🎉
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@endsection