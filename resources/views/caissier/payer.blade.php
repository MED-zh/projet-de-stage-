@extends('caissier.layout')
@section('title', 'Encaisser la facture')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Encaisser la facture</div>
    <div class="page-sub">{{ $facture->numero_facture }}</div>
  </div>
  <a href="{{ route('caissier.factures.index') }}" class="btn btn-ghost">← Retour</a>
</div>

@if($errors->any())
  <div class="alert-error" style="max-width:900px;margin-bottom:16px">
    @foreach($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  </div>
@endif

<form method="POST" action="{{ route('caissier.factures.store', $facture) }}" style="max-width:900px">
  @csrf

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

    {{-- LEFT: info cards --}}
    <div style="display:flex;flex-direction:column;gap:12px">

      {{-- CLIENT --}}
     {{-- CLIENT --}}
<div class="info-card">
  <div class="info-card-title">Client</div>
  <div style="font-size:15px;font-weight:700;color:var(--white);margin-bottom:8px">
    {{ $client?->name ?? '—' }}
  </div>
  <div style="display:grid;grid-template-columns:auto 1fr;gap:4px 10px;font-size:12px;align-items:center">
    <span style="color:var(--muted2)">📞 Téléphone</span>
    <span style="color:var(--text)">{{ $client?->phone ?? 'N/A' }}</span>

    <span style="color:var(--muted2)">📋 Contrat</span>
    <span style="font-family:monospace;color:var(--text)">{{ $facture->contrat_num }}</span>

    @if($contrat?->adresse)
      <span style="color:var(--muted2)">📍 Adresse</span>
      <span style="color:var(--text)">{{ $contrat->adresse }}</span>
    @endif

@if($secteur)
  <span style="color:var(--muted2)">🗺️ Secteur</span>
  <span style="color:var(--text)">{{ $secteur }}</span>
@endif

@if($contrat?->ordre_tournee)
  <span style="color:var(--muted2)">🔄 Tournée</span>
  <span style="color:var(--text)">{{ $contrat->ordre_tournee}}</span>
@endif
  </div>
</div>

      {{-- FACTURE --}}
      <div class="info-card">
        <div class="info-card-title">Facture</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 16px;font-size:12px">
          <div style="color:var(--muted2)">Période</div>
          <div style="color:var(--text);font-weight:600">{{ str_pad($facture->mois,2,'0',STR_PAD_LEFT) }}/{{ $facture->annee }}</div>
          <div style="color:var(--muted2)">Type</div>
          <div>
            @if($facture->type === 'eau')
              <span class="badge blue">💧 Eau</span>
            @else
              <span class="badge amber">⚡ Électricité</span>
            @endif
          </div>
          <div style="color:var(--muted2)">Échéance</div>
          <div style="font-weight:600;color:{{ $facture->status === 'en_retard' ? 'var(--red)' : 'var(--text)' }}">
            {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}
          </div>
          <div style="color:var(--muted2)">Référence</div>
          <div style="font-family:monospace;font-size:11px;color:var(--muted)">{{ $facture->numero_facture }}</div>
        </div>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.06)">
          <div style="font-size:10px;color:var(--muted2);text-transform:uppercase;letter-spacing:.06em">Montant à payer</div>
          <div style="font-family:var(--display);font-size:28px;font-weight:800;color:var(--green);margin-top:4px;line-height:1">
            {{ number_format($facture->montant_ttc, 2) }}
            <span style="font-size:13px;color:var(--muted2)">MAD</span>
          </div>
        </div>
      </div>

    </div>

    {{-- RIGHT: form fields --}}
    <div style="display:flex;flex-direction:column;gap:12px">

      {{-- hidden inputs --}}
      <input type="hidden" name="contrat_num"   value="{{ $facture->contrat_num }}">
      <input type="hidden" name="facture_ids[]" value="{{ $facture->id }}">

      <div class="info-card" style="flex:1;display:flex;flex-direction:column;gap:14px">
        <div class="info-card-title">Paiement</div>

        {{-- NUMERO RECU --}}
        <div>
          <label class="field-label">Numéro de reçu</label>
          <div style="display:flex;gap:8px">
            <input type="text" name="numero_recu" id="numero-recu"
                   value="{{ old('numero_recu', $numeroRecu) }}"
                   style="font-family:monospace;flex:1;{{ $errors->has('numero_recu') ? 'border-color:#f87171' : '' }}">
            <button type="button" onclick="regenerer()" title="Régénérer"
                    class="regen-btn">↻</button>
          </div>
          @error('numero_recu')
            <span class="field-error">{{ $message }}</span>
          @enderror
        </div>

        {{-- DATE --}}
        <div>
          <label class="field-label">Date du paiement</label>
          <input type="date" name="date_paiement"
                 value="{{ old('date_paiement', now()->format('Y-m-d')) }}"
                 style="width:100%;{{ $errors->has('date_paiement') ? 'border-color:#f87171' : '' }}">
          @error('date_paiement')
            <span class="field-error">{{ $message }}</span>
          @enderror
        </div>

        {{-- MONTANT (display only) --}}
        <div>
          <label class="field-label">Montant encaissé</label>
          <div style="padding:9px 14px;background:rgba(0,207,255,0.04);border:1px solid rgba(0,207,255,0.15);border-radius:var(--r);font-family:monospace;font-size:15px;font-weight:700;color:var(--green)">
            {{ number_format($facture->montant_ttc, 2) }} MAD
          </div>
        </div>

        {{-- NOTES --}}
        <div>
          <label class="field-label">Notes <span style="color:var(--muted2);font-weight:400">(optionnel)</span></label>
          <input type="text" name="notes"
                 value="{{ old('notes') }}"
                 style="width:100%"
                 placeholder="Ex: paiement apporté par le fils...">
        </div>

        {{-- SUBMIT --}}
        <div style="padding-top:4px;margin-top:auto">
          <div style="display:flex;gap:10px;align-items:center">
            <button type="submit" class="btn btn-success" style="flex:1;justify-content:center">
              <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M2 5h12v8a1 1 0 01-1 1H3a1 1 0 01-1-1V5zM2 5l1-2h10l1 2"/>
                <path d="M6 9h4"/>
              </svg>
              Confirmer le paiement
            </button>
            <a href="{{ route('caissier.factures.index') }}" class="btn btn-ghost">Annuler</a>
          </div>
        </div>

      </div>
    </div>

  </div>
</form>

<style>
.info-card {
  border: 1px solid var(--border);
  border-radius: var(--r);
  background: var(--s2);
  padding: 1rem 1.1rem;
}
.info-card-title {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--muted2);
  margin-bottom: 10px;
}
.field-label {
  display: block;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: .06em;
  color: var(--muted2);
  margin-bottom: 6px;
  font-weight: 600;
}
.field-error {
  display: block;
  font-size: 11px;
  color: #f87171;
  margin-top: 4px;
}
.regen-btn {
  padding: 0 12px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: var(--r);
  color: var(--muted);
  cursor: pointer;
  font-size: 18px;
  transition: all .15s;
  flex-shrink: 0;
}
.regen-btn:hover {
  border-color: var(--cyan);
  color: var(--cyan);
}
</style>

@push('scripts')
<script>
async function regenerer() {
  const btn = document.querySelector('.regen-btn');
  btn.disabled = true;
  btn.textContent = '...';

  let attempts = 0;
  let found = false;

  while (attempts < 5) {
    const ts   = Date.now().toString(36).toUpperCase();
    const rand = Math.random().toString(36).substring(2, 7).toUpperCase();
    const candidate = 'PAI-' + ts + '-' + rand;

    const res  = await fetch('{{ route("caissier.check-recu") }}?numero=' + encodeURIComponent(candidate));
    const data = await res.json();

    if (!data.exists) {
      document.getElementById('numero-recu').value = candidate;
      document.getElementById('recu-error').textContent = '';
      found = true;
      break;
    }
    attempts++;
  }

  if (!found) {
    document.getElementById('recu-error').textContent = '⚠ Impossible de générer un numéro unique, réessayez.';
  }

  btn.disabled = false;
  btn.textContent = '↻';
}

</script>
@endpush

@endsection