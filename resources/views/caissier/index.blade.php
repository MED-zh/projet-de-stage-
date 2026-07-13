@extends('caissier.layout')
@section('title', 'Paiements')
@section('content')

<style>
  thead th { white-space: nowrap; }
  tbody td { white-space: nowrap; }
</style>

<div class="page-header">
  <div></div>
</div>

{{-- Search bar --}}
<form method="GET" action="{{ route('caissier.paiements.index') }}" style="margin-bottom:16px;display:flex;gap:8px">
  <input
    type="text"
    name="search"
    value="{{ request('search') }}"
    placeholder="Rechercher par N° Facture…"
    class="input"
    style="max-width:320px"
  >
  <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
  @if(request('search'))
    <a href="{{ route('caissier.paiements.index') }}" class="btn btn-ghost btn-sm">✕ Reset</a>
  @endif
</form>

<div class="tbl-card" style="overflow:hidden;display:flex;flex-direction:column">

  <div class="tbl-head">
    <div style="font-size:12px;color:var(--muted)"></div>

    @if($paiements->hasPages())
    <div style="display:flex;align-items:center;gap:6px">
      @if($paiements->onFirstPage())
        <span class="btn btn-ghost btn-sm" style="opacity:.35;pointer-events:none">← Préc</span>
      @else
        <a href="{{ $paiements->previousPageUrl() }}" class="btn btn-ghost btn-sm">← Préc</a>
      @endif
      <span style="font-size:11px;color:var(--muted2)">{{ $paiements->currentPage() }} / {{ $paiements->lastPage() }}</span>
      @if($paiements->hasMorePages())
        <a href="{{ $paiements->nextPageUrl() }}" class="btn btn-ghost btn-sm">Suiv →</a>
      @else
        <span class="btn btn-ghost btn-sm" style="opacity:.35;pointer-events:none">Suiv →</span>
      @endif
    </div>
    @endif
  </div>

  <div style="overflow-x:hidden;overflow-y:auto;max-height:calc(100vh - 280px)">
    <table style="width:100%;border-collapse:collapse;table-layout:fixed">
      <colgroup>
        <col style="width:90px">
        <col style="width:160px">
        <col style="width:110px">
        <col style="width:180px">
        <col style="width:110px">
        <col style="width:110px">
        <col style="width:120px">
        <col style="width:120px">
        <col style="width:60px">
      </colgroup>
      <thead>
        <tr style="position:sticky;top:0;z-index:5;background:var(--s3)">
          <th>N° Reçu</th>
          <th>Client</th>
          <th>Contrat</th>
          <th>N° Facture</th>
          <th>Type</th>
          <th>Montant</th>
          <th>Date paiement</th>
          <th>Caissier</th>
          <th style="text-align:center">Reçu</th>
        </tr>
      </thead>
      <tbody>
        @forelse($paiements as $p)
        <tr>
          <td style="font-family:monospace;font-size:11px;color:var(--blue-hi);overflow:hidden;text-overflow:ellipsis" title="{{ $p->numero_recu }}" >
            {{ $p->numero_recu }}
          </td>
          <td style="font-weight:500;overflow:hidden;text-overflow:ellipsis" title="{{ $p->numero_recu }}">
            {{ $p->contrat?->utilisateur?->name ?? '—' }}
          </td>
          <td style="font-size:12px;color:var(--muted);overflow:hidden;text-overflow:ellipsis" title="{{ $p->contrat?->contrat_num ?? '—' }}">
            {{ $p->contrat?->contrat_num ?? '—' }}
          </td>
          <td style="font-family:monospace;font-size:11px;color:var(--muted2);overflow:hidden;text-overflow:ellipsis">
            {{ $p->facture?->numero_facture ?? '—' }}
          </td>
          <td>
            @if($p->facture?->type === 'eau')
              <span class="badge blue">💧 Eau</span>
            @else
              <span class="badge amber">⚡ Électricité</span>
            @endif
          </td>
          <td style="font-family:var(--display);font-weight:700;color:var(--green);overflow:hidden;text-overflow:ellipsis">
            {{ number_format($p->montant_total, 2) }}
            <span style="font-size:10px;color:var(--muted2)">MAD</span>
          </td>
          <td style="font-size:12px;color:var(--muted)">
            {{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}
          </td>
         <td style="font-size:12px;overflow:hidden;text-overflow:ellipsis" title="{{ $p->caissier?->name ?? '—' }}">
            {{ $p->caissier?->name ?? '—' }}
          </td>
          <td style="text-align:center">
            <a href="{{ route('caissier.paiements.recu', $p->numero_recu) }}" class="btn btn-ghost btn-sm">
              Voir
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="text-align:center;padding:48px;color:var(--muted2)">
            @if(request('search'))
              Aucun paiement trouvé pour "<em>{{ request('search') }}</em>"
            @else
              Aucun paiement enregistré
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@endsection