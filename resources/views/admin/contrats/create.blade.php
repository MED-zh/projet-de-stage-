@extends('admin.layout')
@section('title', 'Nouveau contrat')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Nouveau contrat</div>
    <div class="page-sub">Le client utilisera ce N° contrat lors de son inscription</div>
  </div>
  <a href="{{ route('admin.contrats.index') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="form-card">
  <form method="POST" action="{{ route('admin.contrats.store') }}">
    @csrf
    <div class="form-grid">

      {{-- CONTRAT NUM --}}
      <div class="form-group full">
        <label>N° Contrat</label>
        <div style="display:flex;gap:8px;align-items:center">
          <input
            type="text"
            name="contrat_num"
            value="{{ old('contrat_num', $contratNum) }}"
            
            style="flex:1;font-family:monospace;letter-spacing:.04em;color:var(--cyan);{{ $errors->has('contrat_num') ? 'border-color:#f87171' : '' }}"
          >
          <a href="{{ route('admin.contrats.create') }}"
             style="display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:8px;font-size:12px;font-weight:500;background:rgba(0,207,255,0.08);color:var(--cyan);border:1px solid rgba(0,207,255,0.2);text-decoration:none;white-space:nowrap;flex-shrink:0"
             onmouseover="this.style.background='rgba(0,207,255,0.15)'"
             onmouseout="this.style.background='rgba(0,207,255,0.08)'"
          >
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M1 8A7 7 0 0 1 14.5 5M15 2v3h-3"/>
              <path d="M15 8A7 7 0 0 1 1.5 11M1 14v-3h3"/>
            </svg>
            Générer
          </a>
        </div>
        @error('contrat_num')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
        @if(!$errors->has('contrat_num'))
          <span style="font-size:11px;color:var(--muted2);margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5"><circle cx="8" cy="8" r="6"/><path d="M8 7v4M8 5.5v.5"/></svg>
            Numéro unique garanti — utilisé par le client à l'inscription
          </span>
        @endif
      </div>

{{-- SECTEUR --}}
      <div class="form-group">
        <label>Secteur</label>
        <select name="nom_secteur" style="{{ $errors->has('nom_secteur') ? 'border-color:#f87171' : '' }}">
          <option value="">Choisir un secteur...</option>
          @foreach($secteurs as $secteur)
            <option value="{{ $secteur->nom_secteur }}" {{ old('nom_secteur') == $secteur->nom_secteur ? 'selected' : '' }}>
              {{ $secteur->nom_secteur }}
            </option>
          @endforeach
        </select>
        @error('nom_secteur')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      {{-- ORDRE DE TOURNEE --}}
      <div class="form-group">
        <label>Ordre de tournée</label>
        <input type="number" name="ordre_tournee" value="{{ old('ordre_tournee') }}" placeholder="Ex: 1" 
               style="{{ $errors->has('ordre_tournee') ? 'border-color:#f87171' : '' }}">
        @error('ordre_tournee')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      {{-- ADRESSE --}}
      <div class="form-group full">
        <label>Adresse complète</label>
        <input type="text" name="adresse" value="{{ old('adresse') }}" placeholder="Rue, N° porte, etc."
               style="{{ $errors->has('adresse') ? 'border-color:#f87171' : '' }}">
        @error('adresse')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      {{-- DATE FIN --}}
      <div class="form-group">
        <label>Date de fin</label>
        <input type="date" name="date_fin" value="{{ old('date_fin') }}"
               style="{{ $errors->has('date_fin') ? 'border-color:#f87171' : '' }}">
        @error('date_fin')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      {{-- STATUT --}}
      <div class="form-group">
        <label>Statut</label>
        <select name="statut">
          <option value="actif" {{ old('statut', 'actif') === 'actif' ? 'selected' : '' }}>Actif</option>
          <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
        </select>
      </div>

      {{-- SUBMIT --}}
      <div class="form-group full" style="margin-top:15px; display:flex; gap:10px; align-items:center">
        <button type="submit" class="btn btn-primary">
          <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="margin-right:5px"><path d="M6.5 11.5L2 7l1.5-1.5 3 3 6-6L14 4z"/></svg>
          Créer le contrat
        </button>
        <a href="{{ route('admin.contrats.index') }}" class="btn btn-ghost">Annuler</a>
      </div>

    </div>
  </form>
</div>

<style>
.error-msg { font-size:11px; color:#f87171; margin-top:4px; display:block; }
input[type="date"] { color-scheme: dark; }
</style>

@endsection