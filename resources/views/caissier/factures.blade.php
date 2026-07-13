@extends('caissier.layout')
@section('title', 'Factures à encaisser')
@section('content')

<style>
  thead th { white-space: nowrap; }
  tbody td { white-space: nowrap; }
</style>

<div class="page-header">
  <div>
    <div class="page-title">Factures à encaisser</div>
    <div class="page-sub">Toutes les factures impayées et en retard</div>
  </div>
</div>

@if(session('download_recu'))
  <iframe src="{{ route('caissier.paiements.recu-pdf', session('download_recu')) }}" style="display:none"></iframe>
@endif

@if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- Filtres --}}
<form method="GET" action="{{ route('caissier.factures.index') }}"
      style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:16px">
  <div style="position:relative">
    <input
      type="text"
      name="facture_id"
      value="{{ request('facture_id') }}"
      placeholder="Rechercher par N° facture..."
      style="width:260px;padding-left:30px"
    >
    <svg style="position:absolute;left:9px;top:50%;transform:translateY(-50%);opacity:.4;pointer-events:none"
         width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="6.5" cy="6.5" r="4.5"/><path d="M10.5 10.5l3 3"/>
    </svg>
  </div>
  <select name="type" style="width:160px">
    <option value="">Tous les types</option>
    <option value="eau"         {{ request('type') === 'eau'         ? 'selected' : '' }}>💧 Eau</option>
    <option value="electricite" {{ request('type') === 'electricite' ? 'selected' : '' }}>⚡ Électricité</option>
  </select>
  <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
  <a href="{{ route('caissier.factures.index') }}" class="btn btn-ghost btn-sm">Reset</a>
</form>

<div class="tbl-card" style="overflow:hidden;display:flex;flex-direction:column">

  <div class="tbl-head">
    <div style="font-size:12px;color:var(--muted)">
      <strong style="color:var(--blue-hi);font-family:var(--display)">{{ $factures->total() }}</strong>
      facture{{ $factures->total() > 1 ? 's' : '' }} à encaisser
    </div>
    @if($factures->hasPages())
    <div style="display:flex;align-items:center;gap:6px">
      @if($factures->onFirstPage())
        <span class="btn btn-ghost btn-sm" style="opacity:.35;pointer-events:none">← Préc</span>
      @else
        <a href="{{ $factures->previousPageUrl() }}" class="btn btn-ghost btn-sm">← Préc</a>
      @endif
      <span style="font-size:11px;color:var(--muted2)">{{ $factures->currentPage() }} / {{ $factures->lastPage() }}</span>
      @if($factures->hasMorePages())
        <a href="{{ $factures->nextPageUrl() }}" class="btn btn-ghost btn-sm">Suiv →</a>
      @else
        <span class="btn btn-ghost btn-sm" style="opacity:.35;pointer-events:none">Suiv →</span>
      @endif
    </div>
    @endif
  </div>

  {{-- scrollable wrapper --}}
  <div style="overflow-y:auto;max-height:calc(100vh - 280px);overflow-x:hidden">
     <table style="width:100%;border-collapse:collapse;min-width:900px">
      <thead>
        <tr style="position:sticky;top:0;z-index:5;background:var(--s3)">
          <th>N° Facture</th>
          <th>Client</th>
          <th>Contrat</th>
          <th>Type</th>
          <th>Période</th>
          <th>Montant TTC</th>
          <th>Échéance</th>
          <th>Statut</th>
          <th style="text-align:center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($factures as $facture)
        <tr>
          <td style="font-family:monospace;font-size:11px;color:var(--blue-hi)">{{ $facture->numero_facture }}</td>
          <td>
            <div style="font-weight:500">{{ $facture->client_name }}</div>
            <div style="font-size:11px;color:var(--muted2)">{{ $facture->client_phone }}</div>
          </td>
          <td style="font-size:12px;color:var(--muted)">{{ $facture->contrat_num }}</td>
          <td>
            @if($facture->type === 'eau')
              <span class="badge blue">💧 Eau</span>
            @else
              <span class="badge amber">⚡ Électricité</span>
            @endif
          </td>
          <td style="font-size:12px;color:var(--muted)">
            {{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $facture->annee }}
          </td>
          <td style="font-family:var(--display);font-weight:700">
            {{ number_format($facture->montant_ttc, 2) }}
            <span style="font-size:10px;color:var(--muted2)">MAD</span>
          </td>
          <td style="font-size:12px;color:{{ $facture->status === 'en_retard' ? 'var(--red)' : 'var(--muted)' }}">
            {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}
          </td>
          <td>
            @if($facture->status === 'en_retard')
              <span class="badge red"><span class="badge-dot"></span>En retard</span>
            @else
              <span class="badge amber"><span class="badge-dot"></span>Impayée</span>
            @endif
          </td>
          <td style="text-align:center">
            <a href="{{ route('caissier.factures.payer', $facture->id) }}" class="btn btn-success btn-sm">
              <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M2 5h12v8a1 1 0 01-1 1H3a1 1 0 01-1-1V5zM2 5l1-2h10l1 2"/><path d="M6 9h4"/>
              </svg>
              Encaisser
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="text-align:center;padding:48px;color:var(--muted2)">
            @if(request('facture_id'))
              Aucune facture trouvée pour « {{ request('facture_id') }} »
            @else
              Aucune facture à encaisser ✓
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  <div style="height:1px"></div>
  </div>

</div>

@endsection