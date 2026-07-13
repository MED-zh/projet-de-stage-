@extends('user.layout')
@section('title', 'Mon contrat')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Mon contrat</div>
    <div class="page-sub">Détails de votre contrat de service</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

  {{-- Left card --}}
  <div class="contrat-card">
    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7d8590;margin-bottom:12px;">
      Informations générales
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
      <div class="ct-num">{{ $contrat->contrat_num }}</div>
      <div class="ct-badge">
        <span class="ct-badge-dot"></span>
        {{ ucfirst($contrat->status) }}
      </div>
    </div>

    <div class="ct-sub">{{ $contrat->adresse }}</div>
    <div class="ct-divider"></div>

    <div class="ct-row">
      <span class="ct-key">Secteur</span>
      <span class="ct-val">{{ $contrat->nom_secteur ?? '—' }}</span>
    </div>
    <div class="ct-row">
      <span class="ct-key">Adresse</span>
      <span class="ct-val">{{ $contrat->adresse ?? '—' }}</span>
    </div>
    <div class="ct-row">
      <span class="ct-key">Date de début</span>
      <span class="ct-val">{{ \Carbon\Carbon::parse($contrat->date_debut)->translatedFormat('d F Y') }}</span>
    </div>
    <div class="ct-row">
      <span class="ct-key">Date de fin</span>
      <span class="ct-val">{{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->translatedFormat('d F Y') : '—' }}</span>
    </div>
  </div>

  {{-- Right card --}}
  <div class="contrat-card">
    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7d8590;margin-bottom:12px;">
      Identifiants
    </div>

    <div class="ct-row" style="border-top:none;padding-top:0;">
      <span class="ct-key">N° Contrat</span>
      <span class="ct-val">{{ $contrat->contrat_num }}</span>
    </div>
    <div class="ct-row">
      <span class="ct-key">Ordre de tournée</span>
      <span class="ct-val">{{ $contrat->ordre_tournee ?? '—' }}</span>
    </div>
   

   

    <div class="ct-divider"></div>

    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:#7d8590;margin-bottom:12px;margin-top:16px;">
        Titulaire
    </div>
    <div class="ct-row" style="border-top:none;padding-top:0;">
      <span class="ct-key">Nom</span>
      <span class="ct-val">{{ $user->name }}</span>
    </div>
    <div class="ct-row">
      <span class="ct-key">CIN</span>
      <span class="ct-val">{{ $user->cin ?? '—' }}</span>
    </div>
    <div class="ct-row">
      <span class="ct-key">Téléphone</span>
      <span class="ct-val">{{ $user->phone ?? '—' }}</span>
    </div>
  </div>

</div>
@endsection