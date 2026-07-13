@extends('admin.layout')
@section('title', 'Alertes')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Alertes</div>
    <div class="page-sub">{{ $alerts->total() }} {{ request()->boolean('all') ? 'alertes au total' : 'alertes en cours' }}</div>
  </div>
  <div style="display:flex;gap:8px">
    @if(request()->boolean('all'))
      <a href="{{ route('admin.alerts.index') }}" class="btn btn-primary">
        <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="6.5" cy="6.5" r="4.5"/><path d="M10.5 10.5l3 3"/></svg>
        En cours de traitement
      </a>
    @else
      <a href="{{ route('admin.alerts.index', ['all' => 1]) }}" class="btn btn-ghost">Voir toutes</a>
    @endif
  </div>
</div>

<form method="GET" action="{{ route('admin.alerts.index') }}">
  @if(request()->boolean('all'))
    <input type="hidden" name="all" value="1">
  @endif
  <select name="secteur" onchange="this.form.submit()" class="btn btn-ghost" style="cursor:pointer">
    <option value="">Tous les secteurs</option>
    @foreach($secteurs as $s)
      <option value="{{ $s }}" {{ request('secteur') == $s ? 'selected' : '' }}>{{ $s }}</option>
    @endforeach
  </select>
</form>

@if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="tbl-card" style="overflow:hidden;display:flex;flex-direction:column">
  {{-- Pagination --}}
  @if($alerts->hasPages())
  <div style="display:flex;align-items:center;gap:6px;padding:.75rem 1.25rem;border-top:1px solid var(--border);flex-shrink:0">
    <span style="font-size:12px;color:var(--muted);flex:1">
      Page {{ $alerts->currentPage() }} / {{ $alerts->lastPage() }}
      &nbsp;·&nbsp;
      <strong style="color:var(--blue-hi)">{{ $alerts->total() }}</strong> alertes
    </span>
    @if($alerts->onFirstPage())
      <span class="btn btn-ghost btn-sm" style="opacity:.35;pointer-events:none">← Préc</span>
    @else
      <a href="{{ $alerts->previousPageUrl() }}" class="btn btn-ghost btn-sm">← Préc</a>
    @endif
    @if($alerts->hasMorePages())
      <a href="{{ $alerts->nextPageUrl() }}" class="btn btn-ghost btn-sm">Suiv →</a>
    @else
      <span class="btn btn-ghost btn-sm" style="opacity:.35;pointer-events:none">Suiv →</span>
    @endif
  </div>
  @endif


  <div style="overflow-y:auto;max-height:calc(100vh - 280px)">
    <table style="width:100%">
      <thead style="position:sticky;top:0;z-index:1;background:var(--s3)">
        <tr>
          <th>Contrat</th>
          <th>Technicien</th>
          <th>Description</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alerts as $a)
        <tr>
          <td><span class="badge blue">{{ $a->contrat_num }}</span></td>
          <td style="color:var(--muted)">{{ $a->technicien->name ?? '—' }}</td>
          <td style="color:var(--muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $a->description }}</td>
          <td style="color:var(--muted2)">{{ \Carbon\Carbon::parse($a->date_alert)->format('d/m/Y') }}</td>
          <td>
            @php
              $badge = match($a->status) {
                'en_attente' => 'amber',
                'en_cours'   => 'blue',
                'resolu'     => 'green',
                'annule'     => 'red',
              };
              $label = match($a->status) {
                'en_attente' => 'En attente',
                'en_cours'   => 'En cours',
                'resolu'     => 'Résolu',
                'annule'     => 'Annulé',
              };
            @endphp
            <span class="badge {{ $badge }}"><span class="badge-dot"></span>{{ $label }}</span>
          </td>
          <td>
            <a href="{{ route('admin.alerts.edit', $a->id) }}" class="btn btn-ghost btn-sm">Éditer</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;color:var(--muted2);padding:2rem">
            Aucune alerte en cours
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  
</div>

@endsection