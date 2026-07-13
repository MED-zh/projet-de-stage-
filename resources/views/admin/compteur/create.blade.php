@extends('admin.layout')
@section('title', 'Nouveau compteur')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Nouveau compteur</div>
    <div class="page-sub">Associer un compteur à un contrat (max 2 types par contrat)</div>
  </div>
  <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="form-card">
  <form method="POST" action="{{ route('admin.compteurs.store') }}">
    @csrf
    <div class="form-grid">

      {{-- SECTEUR --}}
      <div class="form-group">
        <label>Secteur</label>
        <select id="secteur-select">
          <option value="">Choisir un secteur...</option>
          @foreach($secteurs as $secteur)
            <option value="{{ $secteur->nom_secteur }}"
              {{ old('_secteur') == $secteur->nom_secteur ? 'selected' : '' }}>
              {{ $secteur->nom_secteur }}
            </option>
          @endforeach
        </select>
        <span class="field-hint">
          <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5"><circle cx="8" cy="8" r="6"/><path d="M8 7v4M8 5.5v.5"/></svg>
          Filtre les contrats disponibles
        </span>
      </div>

      {{-- CONTRAT --}}
      <div class="form-group">
        <label>
          Contrat
          <span style="font-size:11px;color:var(--muted2);font-weight:400;margin-left:4px">(incomplet)</span>
        </label>
        <select name="contrat_num" id="contrat-select"
                style="{{ $errors->has('contrat_num') ? 'border-color:#f87171' : '' }}">
          <option value="">— Choisir d'abord un secteur —</option>
          @foreach($contratsDisponibles as $contrat)
            <option value="{{ $contrat->contrat_num }}"
              {{ old('contrat_num') == $contrat->contrat_num ? 'selected' : '' }}>
              {{ $contrat->contrat_num }} — {{ $contrat->adresse }}
            </option>
          @endforeach
        </select>
        @error('contrat_num')
          <span class="error-msg">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @else
          <span class="field-hint">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5"><circle cx="8" cy="8" r="6"/><path d="M8 7v4M8 5.5v.5"/></svg>
            Contrats avec moins de 2 compteurs
          </span>
        @enderror
      </div>

      {{-- TYPE --}}
      <div class="form-group">
        <label>Type</label>
        <select name="type" id="type-select"
                style="{{ $errors->has('type') ? 'border-color:#f87171' : '' }}">
          <option value="">Choisir...</option>
          <option value="eau"         {{ old('type') === 'eau'         ? 'selected' : '' }}>💧 Eau</option>
          <option value="electricite" {{ old('type') === 'electricite' ? 'selected' : '' }}>⚡ Électricité</option>
        </select>
        @error('type')
          <span class="error-msg">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- EXISTING TYPES BADGE --}}
      <div class="form-group" id="existing-types-row" style="display:none">
        <label style="opacity:0;user-select:none">.</label>
        <div id="existing-types-badge" style="
          display:flex;gap:6px;align-items:center;flex-wrap:wrap;
          padding:9px 14px;border-radius:8px;
          background:rgba(255,255,255,0.04);
          border:1px solid rgba(255,255,255,0.08);
          font-size:12px;color:var(--muted2);
        "></div>
      </div>

      {{-- MATRICULE — full width --}}
      <div class="form-group full">
        <label>Matricule</label>
        <div style="display:flex;gap:8px;align-items:center">
          <input
            type="text"
            name="matricule"
            id="matricule-input"
            value="{{ old('matricule') }}"
            placeholder="Ex: CPT-00123456"
            style="flex:1;font-family:monospace;letter-spacing:.05em;{{ $errors->has('matricule') ? 'border-color:#f87171' : '' }}"
          >
          <button
            type="button"
            id="btn-generate-matricule"
            style="display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:8px;font-size:12px;font-weight:500;background:rgba(0,207,255,0.08);color:var(--cyan);border:1px solid rgba(0,207,255,0.2);white-space:nowrap;flex-shrink:0;cursor:pointer"
            onmouseover="this.style.background='rgba(0,207,255,0.15)'"
            onmouseout="this.style.background='rgba(0,207,255,0.08)'"
          >
            <svg id="gen-icon" width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M1 8A7 7 0 0 1 14.5 5M15 2v3h-3"/>
              <path d="M15 8A7 7 0 0 1 1.5 11M1 14v-3h3"/>
            </svg>
            Générer
          </button>
        </div>
        @error('matricule')
          <span class="error-msg">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- SUBMIT --}}
      <div class="form-group full" style="margin-top:8px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.06);">
        <div style="display:flex;gap:10px;align-items:center">
                    <button type="submit" id="btn-submit" class="btn btn-primary">
              <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="margin-right:6px">
                <path d="M6.5 11.5L2 7l1.5-1.5 3 3 6-6L14 4z"/>
              </svg>
              Créer le compteur
            </button>
          <a href="{{ route('admin.compteurs.index') }}" class="btn btn-ghost">Annuler</a>
        </div>
      </div>

    </div>
  </form>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.error-msg  { font-size:11px;color:#f87171;margin-top:5px;display:flex;align-items:center;gap:4px; }
.field-hint { font-size:11px;color:var(--muted2);margin-top:5px;display:flex;align-items:center;gap:4px; }
.type-badge {
  display:inline-flex;align-items:center;gap:4px;
  padding:3px 8px;border-radius:6px;font-size:11px;font-weight:500;
}
.type-badge.eau         { background:rgba(0,180,255,0.12);color:#38bdf8; }
.type-badge.electricite { background:rgba(250,200,0,0.12);color:#fbbf24; }
</style>

@push('scripts')
<script>
const generateUrl = "{{ route('admin.compteurs.generate-matricule') }}";
const contratsUrl = "{{ url('/admin/compteurs/contrats-disponibles') }}";

let lastContratsData = [];

// ── Matricule generator ──────────────────────────────────────────
document.getElementById('btn-generate-matricule').addEventListener('click', function () {
  const btn  = this;
  const icon = document.getElementById('gen-icon');
  btn.disabled = true;
  icon.style.animation = 'spin .6s linear infinite';
  fetch(generateUrl)
    .then(r => r.json())
    .then(data => {
      document.getElementById('matricule-input').value = data.matricule;
      document.getElementById('matricule-input').style.color = 'var(--cyan)';
      btn.disabled = false;
      icon.style.animation = '';
    })
    .catch(() => { btn.disabled = false; icon.style.animation = ''; });
});

// ── Fetch contrats by secteur only ───────────────────────────────
function fetchContrats() {
  const secteur = document.getElementById('secteur-select').value;
  const select  = document.getElementById('contrat-select');

  select.innerHTML = '<option value="">— Choisir d\'abord un secteur —</option>';
  resetTypeSelect();
  updateExistingBadge(null);
  validateForm();

  if (!secteur) {
    select.disabled  = false;
    lastContratsData = [];
    return;
  }

  select.innerHTML = '<option value="">Chargement...</option>';
  select.disabled  = true;

  fetch(`${contratsUrl}?secteur=${encodeURIComponent(secteur)}`)
    .then(r => r.json())
    .then(data => {
      lastContratsData = data;
      select.disabled  = false;

      if (!data.length) {
        select.innerHTML = '<option value="">Aucun contrat disponible dans ce secteur</option>';
        return;
      }

      select.innerHTML = '<option value="">Choisir un contrat...</option>'
        + data.map(c => {
            const tag = c.existing_types.length
              ? ` [${c.existing_types.map(t => t === 'eau' ? '💧' : '⚡').join('')}]`
              : '';
            return `<option value="${c.contrat_num}">${c.contrat_num} — ${c.adresse}${tag}</option>`;
          }).join('');
    })
    .catch(() => {
      select.disabled  = false;
      select.innerHTML = '<option value="">Erreur de chargement</option>';
    });
}

// ── Reset type dropdown ──────────────────────────────────────────
function resetTypeSelect() {
  const typeSelect = document.getElementById('type-select');
  typeSelect.value = '';
  Array.from(typeSelect.options).forEach(opt => {
    opt.disabled    = false;
    opt.textContent = opt.textContent.replace(' (déjà installé)', '');
  });
}

// ── Update type options when contrat is picked ───────────────────
function updateExistingBadge(contratNum) {
  const row   = document.getElementById('existing-types-row');
  const badge = document.getElementById('existing-types-badge');

  resetTypeSelect();

  if (!contratNum) {
    row.style.display = 'none';
    validateForm();
    return;
  }

  const found = lastContratsData.find(c => c.contrat_num == contratNum);

  if (!found || !found.existing_types.length) {
    row.style.display = 'none';
    validateForm();
    return;
  }

  const typeSelect = document.getElementById('type-select');

  found.existing_types.forEach(takenType => {
    const opt = typeSelect.querySelector(`option[value="${takenType}"]`);
    if (opt) {
      opt.disabled    = true;
      opt.textContent = opt.textContent + ' (déjà installé)';
    }
  });

  // Auto-select if only one type remains
  const available = Array.from(typeSelect.options).filter(o => o.value && !o.disabled);
  if (available.length === 1) {
    typeSelect.value = available[0].value;
  }

  const labels = { eau: '💧 Eau', electricite: '⚡ Électricité' };
  row.style.display = '';
  badge.innerHTML   = '<span style="margin-right:4px">Déjà installé :</span>'
    + found.existing_types.map(t =>
        `<span class="type-badge ${t}">${labels[t] || t}</span>`
      ).join('');

  validateForm();
}

// ── Validate both contrat + type filled ─────────────────────────
function validateForm() {
  const contrat = document.getElementById('contrat-select').value;
  const type    = document.getElementById('type-select').value;
  const btn     = document.getElementById('btn-submit'); // ← target by id, not by type

  const valid       = !!(contrat && type);
  btn.disabled      = !valid;
  btn.style.opacity = valid ? '1' : '0.4';
  btn.style.cursor  = valid ? 'pointer' : 'not-allowed';
}

// ── Event listeners ──────────────────────────────────────────────
document.getElementById('secteur-select').addEventListener('change', fetchContrats);
document.getElementById('contrat-select').addEventListener('change', function () {
  updateExistingBadge(this.value);
});
document.getElementById('type-select').addEventListener('change', validateForm);

// ── Init ─────────────────────────────────────────────────────────
validateForm();
</script>
@endpush

@endsection