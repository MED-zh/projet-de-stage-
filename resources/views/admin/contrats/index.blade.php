@extends('admin.layout')
@section('title', 'Contrats')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Contrats</div>
    <div class="page-sub">{{ $contrats->count() }} contrats enregistrés</div>
  </div>
  <a href="{{ route('admin.contrats.create') }}" class="btn btn-primary">
    <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1v14M1 8h14"/></svg>
    Nouveau contrat
  </a>
</div>

@if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- SEARCH BAR --}}
<form method="GET" action="{{ route('admin.contrats.index') }}">
    <div style="position:relative;display:inline-block">
       
         <select name="secteur" onchange="this.form.submit()"
        style="height:32px;padding:0 12px;font-size:12px;width:auto;min-width:140px;max-width:200px;border:1px solid var(--border2);border-radius:8px;background:var(--s3);color:var(--text);cursor:pointer;outline:none;transition:border-color .2s"
        onfocus="this.style.borderColor='var(--blue)'"
        onblur="this.style.borderColor='var(--border2)'">
            @foreach($secteurs as $s)
                <option value="{{ $s }}" {{ $selectedSecteur === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        <svg style="position:absolute;right:9px;top:50%;transform:translateY(-50%);width:10px;height:10px;opacity:.4;pointer-events:none" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6l4 4 4-4"/></svg>
    </div>
</form>
<div class="tbl-card">
  <table>
    <thead>
      <tr>
        <th>N° Contrat</th>
        <th>Client lié</th>
        <th>Zone</th>
        <th>Début</th>
        <th>Fin</th>
        <th>tourner</th>
      
       
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($contrats as $c)
      <tr>
        <td style="font-family:var(--display);color:var(--blue-hi)">{{ $c->contrat_num }}</td>
        <td>
          @if($c->utilisateur)
            <div style="display:flex;align-items:center;gap:6px">
              <div style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#fff;flex-shrink:0">
                {{ strtoupper(substr($c->utilisateur->name, 0, 2)) }}
              </div>
              {{ $c->utilisateur->name }}
            </div>
          @else
            <span style="color:var(--muted2);font-style:italic">Non assigné</span>
          @endif
        </td>
        <td>{{ $c->nom_secteur ?? '—' }}</td>
        <td style="color:var(--muted)">{{ \Carbon\Carbon::parse($c->date_debut)->format('d/m/Y') }}</td>
        <td style="color:var(--muted)">{{ \Carbon\Carbon::parse($c->date_fin)->format('d/m/Y') }}</td>
        <td>{{ $c->ordre_tournee ?? '—' }}</td>
          <td>
              <span class="badge 
                @if($c->status === 'actif') green 
                @elseif($c->status === 'suspendu') blue 
                @else red @endif">
                <span class="badge-dot"></span>
                {{ ucfirst($c->status ?? 'actif') }}
              </span>
        </td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="{{ route('admin.contrats.edit', $c->id) }}" class="btn btn-ghost btn-sm">Éditer</a>
            <form method="POST" action="{{ route('admin.contrats.destroy', $c->id) }}" onsubmit="return confirm('Supprimer ce contrat ?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Suppr.</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" style="text-align:center;color:var(--muted2);padding:2rem">
          @if($search)
            Aucun contrat trouvé pour "{{ $search }}"
          @else
            Aucun contrat enregistré
          @endif
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection