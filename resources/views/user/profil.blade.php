@extends('user.layout')
@section('title', 'Mon profil')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Mon profil</div>
    <div class="page-sub">Informations de votre compte</div>
  </div>
</div>

{{-- Hero --}}
<div style="display:flex;align-items:center;gap:20px;padding-bottom:28px;border-bottom:1px solid #21262d;margin-bottom:28px;">
  <div style="width:64px;height:64px;border-radius:50%;background:#1c6feb;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:#fff;flex-shrink:0;">
    {{ strtoupper(substr($user->name, 0, 2)) }}
  </div>
  <div>
    <div style="font-size:20px;font-weight:600;color:#e6edf3;">{{ $user->name }}</div>
    <div style="font-size:13px;color:#7d8590;margin-top:3px;">Client · {{ optional($user->contrat)->zone ?? 'N/A' }}</div>
    <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(35,134,54,0.15);color:#3fb950;font-size:11px;padding:3px 9px;border-radius:20px;border:1px solid rgba(63,185,80,0.25);margin-top:6px;">
      <span style="width:5px;height:5px;border-radius:50%;background:#3fb950;display:inline-block;"></span>
      Compte actif
    </div>
  </div>
</div>

{{-- Two-column fields --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">

  {{-- Left --}}
  <div style="padding-right:32px;border-right:1px solid #21262d;">
    <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#484f58;margin-bottom:16px;">Coordonnées</div>

    @foreach([
      ['Nom complet',   $user->name],
      ['Email',         $user->email],
      ['Téléphone',     $user->phone ?? '—'],
      ['Membre depuis', $user->created_at->format('F Y')],
    ] as [$key, $val])
    <div style="display:flex;flex-direction:column;gap:4px;padding:14px 0;border-bottom:1px solid #21262d;">
      <span style="font-size:11px;color:#7d8590;">{{ $key }}</span>
      <span style="font-size:14px;color:#e6edf3;font-weight:500;">{{ $val }}</span>
    </div>
    @endforeach
  </div>

  {{-- Right --}}
  <div style="padding-left:32px;">
    <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#484f58;margin-bottom:16px;">Informations du compte</div>
@foreach([
  ['CIN',        $user->cin ?? '—'],
  ['Adresse',    $user->adresse ?? '—'],
  ['N° Contrat', $user->contrat_num ?? '—'],
  ['Secteur / tournee',    ($user->contrat->nom_secteur ?? '—') . '  ·   ' . ($user->contrat->ordre_tournee ?? '—')],
] as [$key, $val])
    <div style="display:flex;flex-direction:column;gap:4px;padding:14px 0;border-bottom:1px solid #21262d;">
      <span style="font-size:11px;color:#7d8590;">{{ $key }}</span>
      <span style="font-size:14px;color:#e6edf3;font-weight:500;">{{ $val }}</span>
    </div>
    @endforeach
  </div>

</div>
@endsection