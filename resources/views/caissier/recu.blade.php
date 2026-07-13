@extends('caissier.layout')
@section('title', 'Reçu de paiement')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Reçu de paiement</div>
    <div class="page-sub">{{ $numero }}</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('caissier.paiements.recu-pdf', $numero) }}" class="btn btn-primary">
      <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6">
        <path d="M8 2v8M5 7l3 3 3-3M3 13h10"/>
      </svg>
      Télécharger PDF
    </a>
    <a href="{{ route('caissier.factures.index') }}" class="btn btn-ghost">← Retour</a>
  </div>
</div>

@if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;max-width:900px;margin-bottom:1.25rem">

  {{-- CLIENT --}}
  <div style="border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem">
    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);margin-bottom:10px">Client</div>
    <div style="font-family:var(--display);font-size:15px;font-weight:700;color:var(--white);margin-bottom:8px">
      {{ $client?->name ?? '—' }}
    </div>
    <div style="display:grid;grid-template-columns:auto 1fr;gap:4px 10px;font-size:12px;align-items:center">
      <span style="color:var(--muted2)">📞</span>
      <span style="color:var(--muted)">{{ $client?->phone ?? 'N/A' }}</span>
      <span style="color:var(--muted2)">✉️</span>
      <span style="color:var(--muted)">{{ $client?->email ?? '—' }}</span>
      @if($client?->cin)
        <span style="color:var(--muted2)">🪪</span>
        <span style="color:var(--muted);font-family:monospace">{{ $client->cin }}</span>
      @endif
    </div>
  </div>

  {{-- CONTRAT --}}
  <div style="border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem">
    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);margin-bottom:10px">Contrat</div>
    <div style="font-family:monospace;font-size:14px;font-weight:700;color:var(--white);margin-bottom:8px">
      {{ $contrat?->contrat_num ?? '—' }}
    </div>
    <div style="display:grid;grid-template-columns:auto 1fr;gap:4px 10px;font-size:12px;align-items:start">
      @if($contrat?->adresse)
        <span style="color:var(--muted2)">📍</span>
        <span style="color:var(--muted)">{{ $contrat->adresse }}</span>
      @endif
      @if($contrat?->nom_secteur)
        <span style="color:var(--muted2)">🗺️</span>
        <span style="color:var(--muted)">{{ $contrat->nom_secteur }}</span>
      @endif
      @if($contrat?->ordre_tournee)
        <span style="color:var(--muted2)">🔄</span>
        <span style="color:var(--muted)">Tournée {{ $contrat->ordre_tournee }}</span>
      @endif
      <span style="color:var(--muted2)">📊</span>
      <span>
        @if($contrat?->status === 'actif')
          <span class="badge green">Actif</span>
        @elseif($contrat?->status === 'suspendu')
          <span class="badge amber">Suspendu</span>
        @else
          <span class="badge red">Résilié</span>
        @endif
      </span>
    </div>
  </div>

  {{-- RECU --}}
  <div style="border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem">
    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);margin-bottom:10px">Reçu</div>
    <div style="display:grid;grid-template-columns:auto 1fr;gap:6px 10px;font-size:12px;align-items:center">
      <span style="color:var(--muted2)">N°</span>
      <span style="font-family:monospace;font-weight:700;color:var(--blue-hi)">{{ $numero }}</span>

      <span style="color:var(--muted2)">Date</span>
      <span style="color:var(--text);font-weight:600">
        {{ $paiements->first()->date_paiement->format('d/m/Y') }}
      </span>

      <span style="color:var(--muted2)">Caissier</span>
      <span style="color:var(--text)">{{ $caissier?->name ?? '—' }}</span>

      @if($caissier?->cin)
        <span style="color:var(--muted2)">CIN</span>
        <span style="font-family:monospace;color:var(--muted)">{{ $caissier->cin }}</span>
      @endif

      <span style="color:var(--muted2)">Factures</span>
      <span style="color:var(--text)">{{ $paiements->count() }}</span>
    </div>
  </div>

</div>

{{-- TABLEAU FACTURES --}}
<div class="tbl-card" style="max-width:900px">
  <div class="tbl-head">
    <div style="font-family:var(--display);font-size:14px;font-weight:700;color:var(--white)">
      Factures soldées
    </div>
  </div>
  <table>
    <thead>
      <tr>
        <th>N° Facture</th>
        <th>Période</th>
        <th>Type</th>
        <th style="text-align:right">Montant TTC</th>
      </tr>
    </thead>
    <tbody>
      @foreach($paiements as $p)
      <tr>
        <td style="font-family:monospace;font-size:11px;color:var(--blue-hi)">
          {{ $p->facture?->numero_facture ?? '—' }}
        </td>
        <td style="color:var(--muted);font-size:12px">
          {{ str_pad($p->facture?->mois, 2, '0', STR_PAD_LEFT) }}/{{ $p->facture?->annee }}
        </td>
        <td>
          @if($p->facture?->type === 'eau')
            <span class="badge blue">💧 Eau</span>
          @else
            <span class="badge amber">⚡ Électricité</span>
          @endif
        </td>
        <td style="text-align:right;font-family:var(--display);font-weight:700">
          {{ number_format($p->montant_total, 2) }}
          <span style="font-size:10px;color:var(--muted2)">MAD</span>
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr style="border-top:1px solid var(--border2)">
        <td colspan="3" style="padding:12px 1.25rem;font-size:13px;font-weight:600;color:var(--white)">
          Total encaissé
        </td>
        <td style="padding:12px 1.25rem;text-align:right;font-family:var(--display);font-size:18px;font-weight:700;color:var(--green)">
          {{ number_format($montantTotal, 2) }}
          <span style="font-size:11px;color:var(--muted2)">MAD</span>
        </td>
      </tr>
    </tfoot>
  </table>
</div>

{{-- NOTES --}}
@if($paiements->first()->notes)
  <div style="max-width:900px;margin-top:12px;padding:10px 14px;border-radius:8px;
              background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);
              font-size:12px;color:var(--muted)">
    📝 {{ $paiements->first()->notes }}
  </div>
@endif

@endsection