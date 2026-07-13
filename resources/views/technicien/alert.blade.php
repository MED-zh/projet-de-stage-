@extends('technicien.layout')
@section('title', 'Alertes')
@section('content')
<style>
.page-title{font-family:var(--display);font-size:20px;font-weight:700;color:var(--white);letter-spacing:-.5px;margin-bottom:4px}
.page-sub{font-size:13px;color:var(--muted);margin-bottom:1.5rem}
.t-alert{padding:10px 16px;border-radius:8px;font-size:13px;margin-bottom:1rem;display:flex;align-items:center;gap:8px}
.t-alert-success{background:var(--green-lo);border:1px solid rgba(74,222,128,.2);color:var(--green)}
.card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem;margin-bottom:1rem}
.card-title{font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);margin-bottom:1rem;font-weight:600}
.form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:.75rem}
.form-label{font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);font-weight:600}
.form-input{height:34px;padding:0 12px;border-radius:8px;font-size:13px;background:var(--s3);border:1px solid var(--border2);color:var(--text);font-family:var(--body);outline:none;width:100%}
.form-input:focus{border-color:var(--blue)}
select.form-input option{background:var(--s3)}
textarea.form-input{height:90px;padding:10px 12px;resize:vertical}
.btn-danger{height:36px;padding:0 16px;border-radius:8px;font-size:12px;font-weight:600;background:var(--red-lo);border:1px solid rgba(248,113,113,.3);color:var(--red);cursor:pointer;font-family:var(--body)}
.btn-danger:hover{background:rgba(248,113,113,.22)}
/* Historique */
.alerte-row{display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid rgba(61,127,255,.05)}
.alerte-row:last-child{border-bottom:none}
.alerte-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:4px}
.dot-en_attente{background:var(--amber)}
.dot-en_cours{background:var(--blue-hi)}
.dot-resolu{background:var(--green)}
.dot-annule{background:var(--muted2)}
.alerte-desc{font-size:13px;color:var(--text);margin-bottom:3px}
.alerte-meta{font-size:11px;color:var(--muted2)}
.badge-status{display:inline-block;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:600;margin-left:6px}
.badge-en_attente{background:var(--amber-lo);color:var(--amber)}
.badge-en_cours{background:var(--blue-lo);color:var(--blue-hi)}
.badge-resolu{background:var(--green-lo);color:var(--green)}
.badge-annule{background:rgba(200,210,255,.08);color:var(--muted)}
</style>

<div class="page-title">Alertes</div>
<div class="page-sub">Signalez un problème ou consultez vos alertes envoyées</div>

@if(session('success_alert'))
    <div class="t-alert t-alert-success">✓ {{ session('success_alert') }}</div>
@endif

{{-- Formulaire alerte --}}
<div class="card">
    <div class="card-title" style="color:var(--red)">⚠ Signaler un problème</div>
    <form method="POST" action="{{ route('tech.alerte.store') }}">
        @csrf
            <div class="form-group">
                <label class="form-label">N° Contrat concerné</label>
                    <select name="contrat_num" class="form-input" required>
                        <option value="" disabled selected>-- Sélectionner un contrat --</option>
                        @foreach ($contrats as $num)
                            <option value="{{ $num }}">{{ $num }}</option>
                        @endforeach
                    </select>
          </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-input"
                      placeholder="Décrivez le problème (compteur défectueux, fuite, accès impossible...)" required></textarea>
        </div>
        <button type="submit" class="btn-danger">⚠ Envoyer l'alerte</button>
    </form>
</div>

{{-- Historique --}}
<div class="card">
    <div class="card-title">Historique de mes alertes</div>
    @forelse ($alertes as $alerte)
    <div class="alerte-row">
        <div class="alerte-dot dot-{{ $alerte->status }}"></div>
        <div>
            <div class="alerte-desc">{{ $alerte->description }}</div>
            <div class="alerte-meta">
                Contrat : {{ $alerte->contrat_num }} ·
                {{ \Carbon\Carbon::parse($alerte->date_alert)->format('d/m/Y') }}
                <span class="badge-status badge-{{ $alerte->status }}">{{ ucfirst(str_replace('_', ' ', $alerte->status)) }}</span>
            </div>
        </div>
    </div>
    @empty
    <p style="color:var(--muted2);font-size:13px">Aucune alerte envoyée.</p>
    @endforelse
</div>
@endsection