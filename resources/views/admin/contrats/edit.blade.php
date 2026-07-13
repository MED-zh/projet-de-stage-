@extends('admin.layout')
@section('title', 'Éditer contrat')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Éditer — {{ $contrat->contrat_num }}</div>
    <div class="page-sub">Identifiant unique : <span style="color:var(--cyan)">{{ $contrat->contrat_num }}</span></div>
  </div>
  <a href="{{ route('admin.contrats.index') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="form-card">
  <form method="POST" action="{{ route('admin.contrats.update', $contrat->id) }}">
    @csrf 
    @method('PUT')
    
    <div class="form-grid">
      
      {{-- SECTEUR --}}
      <div class="form-group">
        <label>Secteur</label>
        <select name="nom_secteur" style="{{ $errors->has('nom_secteur') ? 'border-color:#f87171' : '' }}">
          @foreach($secteurs as $secteur)
            <option value="{{ $secteur->nom_secteur }}" 
              {{ old('nom_secteur', $contrat->nom_secteur) == $secteur->nom_secteur ? 'selected' : '' }}>
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
        <input type="number" name="ordre_tournee" 
               value="{{ old('ordre_tournee', $contrat->ordre_tournee) }}" 
               style="{{ $errors->has('ordre_tournee') ? 'border-color:#f87171' : '' }}">
        @error('ordre_tournee')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      {{-- ADRESSE --}}
      <div class="form-group full">
        <label>Adresse complète</label>
        <input type="text" name="adresse" 
               value="{{ old('adresse', $contrat->adresse) }}"
               style="{{ $errors->has('adresse') ? 'border-color:#f87171' : '' }}">
        @error('adresse')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

     {{-- STATUT --}}
<div class="form-group">
  <label>Statut du contrat</label>
  <select name="status" style="{{ $errors->has('status') ? 'border-color:#f87171' : '' }}">
    <option value="actif" {{ old('status', $contrat->status) === 'actif' ? 'selected' : '' }}>
      Actif
    </option>
    <option value="suspendu" {{ old('status', $contrat->status) === 'suspendu' ? 'selected' : '' }}>
      Suspendu
    </option>
    <option value="resilie" {{ old('status', $contrat->status) === 'resilie' ? 'selected' : '' }}>
      Résilié
    </option>
  </select>
  @error('status')
    <span style="font-size:11px; color:#f87171; margin-top:4px; display:block;">
      {{ $message }}
    </span>
  @enderror
</div>

      {{-- DATE DE FIN --}}
      <div class="form-group">
        <label>Date de fin</label>
        <input type="date" name="date_fin" 
               value="{{ old('date_fin', $contrat->date_fin ? date('Y-m-d', strtotime($contrat->date_fin)) : '') }}"
               style="{{ $errors->has('date_fin') ? 'border-color:#f87171' : '' }}">
        @error('date_fin')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      {{-- BOUTONS --}}
      <div class="form-group full" style="margin-top:12px; display:flex; gap:10px; align-items:center">
        <button type="submit" class="btn btn-primary">
          <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="margin-right:5px"><path d="M6.5 11.5L2 7l1.5-1.5 3 3 6-6L14 4z"/></svg>
          Mettre à jour le contrat
        </button>
        <a href="{{ route('admin.contrats.index') }}" class="btn btn-ghost">Annuler</a>
      </div>

    </div>
  </form>
</div>

<style>
.error-msg { font-size:11px; color:#f87171; margin-top:4px; display:block; }
input[type="date"] { color-scheme: dark; }
/* Style pour simuler le champ readonly du numéro si besoin */
.readonly-info { background: rgba(255,255,255,0.05); cursor: not-allowed; }
</style>

@endsection