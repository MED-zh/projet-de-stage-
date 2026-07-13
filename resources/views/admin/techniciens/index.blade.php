@extends('admin.layout')
@section('title', 'Techniciens')
@section('content')

<div class="page-header">
  <div>
    <div class="page-title">Techniciens</div>
    <div class="page-sub">{{ $techniciens->count() }} techniciens enregistrés</div>
  </div>
  <a href="{{ route('admin.techniciens.create') }}" class="btn btn-primary">
    <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1v14M1 8h14"/></svg>
    Nouveau technicien
  </a>
</div>
<form method="GET" action="{{ route('admin.techniciens.index') }}" style="margin-bottom:16px;display:flex;gap:8px;align-items:center">
    <select name="secteur" onchange="this.form.submit()"
        style="height:32px;padding:0 12px;font-size:12px;width:auto;min-width:140px;max-width:200px;border:1px solid var(--border2);border-radius:8px;background:var(--s3);color:var(--text);cursor:pointer;outline:none;transition:border-color .2s"
        onfocus="this.style.borderColor='var(--blue)'"
        onblur="this.style.borderColor='var(--border2)'">
        @foreach($secteurs as $s)
            <option value="{{ $s }}" {{ $selectedSecteur === $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>

    @if(request()->has('all'))
        <a href="{{ route('admin.techniciens.index') }}" class="btn btn-ghost btn-sm">Par secteur</a>
    @else
        <a href="{{ route('admin.techniciens.index', ['all' => 1]) }}" class="btn btn-ghost btn-sm">Afficher tous</a>
    @endif
</form>

@if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif


<div class="tbl-card">
  <table>
<thead>
  <tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Téléphone</th>
    <th>CIN</th>
    <th>Secteur</th>
    <th>Depuis</th>
    <th>Actions</th>
  </tr>
</thead>
<tbody>
  @forelse($techniciens as $t)
  <tr>
    <td>
      <div style="display:flex;align-items:center;gap:8px">
        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;flex-shrink:0">
          {{ strtoupper(substr($t->name, 0, 2)) }}
        </div>
        {{ $t->name }}
      </div>
    </td>
    <td style="color:var(--muted)">{{ $t->email }}</td>
    <td style="color:var(--muted)">{{ $t->phone ?? '—' }}</td>
    <td><span class="badge blue">{{ $t->cin ?? '—' }}</span></td>
    <td style="color:var(--muted)">{{ $t->nom_secteur ?? '—' }}</td>
    <td style="color:var(--muted2)">{{ $t->created_at->format('d/m/Y') }}</td>
    <td>
      <div style="display:flex;gap:6px">
        <a href="{{ route('admin.techniciens.edit', $t->id) }}" class="btn btn-ghost btn-sm">Éditer</a>
        <form method="POST" action="{{ route('admin.techniciens.destroy', $t->id) }}" onsubmit="return confirm('Supprimer ce technicien ?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">Suppr.</button>
        </form>
      </div>
    </td>
  </tr>
  @empty
  <tr><td colspan="7" style="text-align:center;color:var(--muted2);padding:2rem">Aucun technicien trouvé</td></tr>
  @endforelse
    </tbody>
  </table>
</div>

@endsection