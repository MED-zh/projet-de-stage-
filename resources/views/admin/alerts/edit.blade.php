@extends('admin.layout')
@section('title', 'Éditer alerte')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Éditer alerte</div>
    <div class="page-sub">Contrat {{ $alert->contrat_num }}</div>
  </div>
  <a href="{{ route('admin.alerts.index') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="form-card">
  <form method="POST" action="{{ route('admin.alerts.update', $alert->id) }}">
    @csrf
     @method('PUT')
    <div class="form-grid">

      {{-- STATUT --}}
      <div class="form-group">
        <label>Statut</label>
        <select name="status" style="{{ $errors->has('status') ? 'border-color:#f87171' : '' }}">
          @foreach(['en_attente' => 'En attente', 'en_cours' => 'En cours', 'resolu' => 'Résolu', 'annule' => 'Annulé'] as $val => $lbl)
            <option value="{{ $val }}" {{ old('status', $alert->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
          @endforeach
        </select>
        @error('status')<span class="form-error">{{ $message }}</span>@enderror
      </div>

      {{-- TECHNICIEN --}}
      <div class="form-group">
        <label>Technicien</label>
        <select name="tech_cin" style="{{ $errors->has('tech_cin') ? 'border-color:#f87171' : '' }}">
          <option value="">Choisir un technicien...</option>
          @foreach($techniciens as $t)
            <option value="{{ $t->cin }}" {{ old('tech_cin', $alert->tech_cin) === $t->cin ? 'selected' : '' }}>
              {{ $t->name }} — {{ $t->cin }}
            </option>
          @endforeach
        </select>
        @error('tech_cin')<span class="form-error">{{ $message }}</span>@enderror
      </div>

      {{-- SUBMIT --}}
      <div class="form-group full" style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="btn btn-primary">
          <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M6.5 11.5L2 7l1.5-1.5 3 3 6-6L14 4z"/></svg>
          Enregistrer
        </button>
        <a href="{{ route('admin.alerts.index') }}" class="btn btn-ghost">Annuler</a>
      </div>

    </div>
  </form>
</div>

@endsection