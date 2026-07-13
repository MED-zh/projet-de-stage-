@extends('admin.layout')
@section('title', 'Nouveau technicien')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Nouveau technicien</div>
    <div class="page-sub">Créer un compte technicien</div>
  </div>
  <a href="{{ route('admin.techniciens.index') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="form-card">
  <form method="POST" action="{{ route('admin.techniciens.store') }}">
    @csrf
    <input type="hidden" name="role" value="technicien">
    <div class="form-grid">

      {{-- NAME --}}
      <div class="form-group">
        <label>Nom complet</label>
        <input type="text" name="name" value="{{ old('name') }}"
               placeholder="ex: Ahmed Benali"
               style="{{ $errors->has('name') ? 'border-color:#f87171' : '' }}">
        @error('name')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- CIN --}}
      <div class="form-group">
        <label>CIN</label>
        <input type="text" name="cin" value="{{ old('cin') }}"
               placeholder="ex: AB123456"
               style="{{ $errors->has('cin') ? 'border-color:#f87171' : '' }}">
        @error('cin')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- EMAIL --}}
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="ex: ahmed@aquawatt.ma"
               style="{{ $errors->has('email') ? 'border-color:#f87171' : '' }}">
        @error('email')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- PHONE --}}
      <div class="form-group">
        <label>Téléphone</label>
        <input type="text" name="phone" value="{{ old('phone') }}"
               placeholder="ex: +212 6XX XXX XXX"
               style="{{ $errors->has('phone') ? 'border-color:#f87171' : '' }}">
        @error('phone')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- ADRESSE --}}
      <div class="form-group full">
        <label>Adresse</label>
        <input type="text" name="adresse" value="{{ old('adresse') }}"
               placeholder="ex: 12 Rue Hassan II, Safi"
               style="{{ $errors->has('adresse') ? 'border-color:#f87171' : '' }}">
        @error('adresse')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
          </div>
                    <div class="form-group">
                  <label>Secteur</label>
                  <select name="nom_secteur" style="{{ $errors->has('nom_secteur') ? 'border-color:#f87171' : '' }}">
                      <option value="">Choisir un secteur...</option>
                      @foreach($secteurs as $s)
                          <option value="{{ $s }}" {{ old('nom_secteur') == $s ? 'selected' : '' }}>
                              {{ $s }}
                          </option>
                      @endforeach
                  </select>
                  @error('nom_secteur')
                      <span class="error-msg">{{ $message }}</span>
                  @enderror
          </div>

      {{-- PASSWORD --}}
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password"
               placeholder="Minimum 8 caractères"
               style="{{ $errors->has('password') ? 'border-color:#f87171' : '' }}">
        @error('password')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- PASSWORD CONFIRM --}}
      <div class="form-group">
        <label>Confirmer mot de passe</label>
        <input type="password" name="password_confirmation"
               placeholder="Répéter le mot de passe"
               style="{{ $errors->has('password_confirmation') ? 'border-color:#f87171' : '' }}">
        @error('password_confirmation')
          <span style="font-size:11px;color:#f87171;margin-top:4px;display:flex;align-items:center;gap:4px">
            <svg width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5M8 10.5v.5"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- SUBMIT --}}
      <div class="form-group full" style="margin-top:8px;display:flex;gap:10px;align-items:center">
        <button type="submit" class="btn btn-primary">
          <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M6.5 11.5L2 7l1.5-1.5 3 3 6-6L14 4z"/></svg>
          Créer le technicien
        </button>
        <a href="{{ route('admin.techniciens.index') }}" class="btn btn-ghost">Annuler</a>
      </div>

    </div>
  </form>
</div>

@endsection