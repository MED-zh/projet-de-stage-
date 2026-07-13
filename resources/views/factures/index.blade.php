@extends('user.layout')

@section('title', 'Mes Factures')

@section('content')
<style>
.main { overflow:hidden !important; }
.fct-wrap * { box-sizing:border-box; }
.fct-title { font-family:var(--display); font-size:20px; font-weight:700; color:var(--white); letter-spacing:-.5px; margin-bottom:4px; }
.fct-sub   { font-size:13px; color:var(--muted); margin-bottom:1.5rem; }
.fct-filters { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:.75rem; align-items:center; }
.fct-select {
    height:32px; padding:0 28px 0 10px; border-radius:8px; font-size:12px;
    background-color:var(--s2);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='rgba(200,210,255,0.4)' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 8px center;
    border:1px solid var(--border2); color:var(--text);
    font-family:var(--body); appearance:none; outline:none; cursor:pointer;
}
.fct-select:focus { border-color:var(--blue); }
.fct-select option { background:var(--s1); }
.fct-btn-submit {
    height:32px; padding:0 16px; border-radius:8px; font-size:12px; font-weight:600;
    background:var(--blue); border:none; color:#fff; cursor:pointer;
    font-family:var(--body); box-shadow:0 0 14px rgba(61,127,255,.3);
}
.fct-btn-reset {
    height:32px; padding:0 14px; border-radius:8px; font-size:12px; font-weight:500;
    background:transparent; border:1px solid var(--border2); color:var(--muted);
    font-family:var(--body); text-decoration:none; display:inline-flex; align-items:center;
}
.fct-btn-reset:hover { background:var(--blue-lo); color:var(--text); }

/* ── Nav buttons ── */
.fct-nav-btn {
    height:32px; padding:0 14px; border-radius:8px; font-size:12px; font-weight:500;
    background:var(--s2); border:1px solid var(--border2); color:var(--muted);
    text-decoration:none; display:inline-flex; align-items:center; white-space:nowrap;
}
.fct-nav-btn:hover { background:var(--blue-lo); color:var(--text); border-color:var(--blue); }
.fct-nav-disabled { opacity:.35; pointer-events:none; }

/* ── Compteur ── */
.fct-count { font-size:12px; color:var(--muted); margin-bottom:1rem; }
.fct-count strong { color:var(--blue-hi); font-family:var(--display); }

/* ── Table wrapper ── */
.fct-table-wrap {
    border:1px solid var(--border); border-radius:var(--r);
    background:var(--s2); width:100%;
    overflow-x:auto; overflow-y:auto; max-height:calc(100vh - 260px);
}
.fct-table { width:100%; border-collapse:collapse; min-width:750px; }
.fct-table thead tr { border-bottom:1px solid var(--border2); }
.fct-table thead th {
    padding:10px 14px; font-size:10px; font-weight:600;
    text-transform:uppercase; letter-spacing:.08em;
    color:var(--muted2); text-align:left; white-space:nowrap;
    background:var(--s3); position:sticky; top:0; z-index:1;
}
.fct-table tbody tr { border-bottom:1px solid rgba(61,127,255,0.05); }
.fct-table tbody tr:last-child { border-bottom:none; }
.fct-table tbody tr:hover { background:rgba(61,127,255,0.04); }
.fct-table tbody td { padding:11px 14px; font-size:13px; color:var(--text); vertical-align:middle; white-space:nowrap; }
.fct-num   { font-family:var(--display); font-size:11px; font-weight:700; color:var(--blue-hi); }
.fct-muted { color:var(--muted); font-size:12px; }
.fct-amt   { font-family:var(--display); font-size:14px; font-weight:700; color:var(--white); }
.fct-unit  { font-size:10px; color:var(--muted2); margin-left:2px; }
.fct-badge { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:99px; font-size:10px; font-weight:600; line-height:1.6; white-space:nowrap; }
.fct-badge-eau    { background:rgba(0,207,255,.1);   color:#00cfff; border:1px solid rgba(0,207,255,.2); }
.fct-badge-elec   { background:rgba(61,127,255,.12); color:#6699ff; border:1px solid rgba(61,127,255,.2); }
.fct-badge-payee  { background:rgba(74,222,128,.12); color:#4ade80; border:1px solid rgba(74,222,128,.2); }
.fct-badge-impayee{ background:rgba(248,113,113,.12);color:#f87171; border:1px solid rgba(248,113,113,.2); }
.fct-badge-retard { background:rgba(251,191,36,.12); color:#fbbf24; border:1px solid rgba(251,191,36,.2); }
.fct-dl {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 12px; border-radius:7px; font-size:11px; font-weight:600;
    background:rgba(61,127,255,.1); border:1px solid var(--border2); color:#6699ff;
    text-decoration:none;
}
.fct-dl:hover { background:var(--blue); border-color:var(--blue); color:#fff; }
.fct-dl svg { width:11px; height:11px; }
.fct-empty { padding:48px; text-align:center; color:var(--muted2); font-size:13px; }
</style>

<div class="fct-wrap">

    <div class="fct-title">Mes Factures</div>
    <div class="fct-sub">Historique et téléchargement de vos factures</div>

    {{-- Filtres + Previous/Next à droite --}}
    <form method="GET" action="{{ route('factures.index') }}" class="fct-filters">
        <select name="type" class="fct-select">
            <option value="">Tous les types</option>
            <option value="eau"         {{ request('type') === 'eau'         ? 'selected' : '' }}>💧 Eau</option>
            <option value="electricite" {{ request('type') === 'electricite' ? 'selected' : '' }}>⚡ Électricité</option>
        </select>
        <select name="status" class="fct-select">
            <option value="">Tous les statuts</option>
            <option value="impayee"   {{ request('status') === 'impayee'   ? 'selected' : '' }}>Impayée</option>
            <option value="payee"     {{ request('status') === 'payee'     ? 'selected' : '' }}>Payée</option>
            <option value="en_retard" {{ request('status') === 'en_retard' ? 'selected' : '' }}>En retard</option>
        </select>
            <select name="annee" class="fct-select">
                <option value="">Toutes les années</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        <button type="submit" class="fct-btn-submit">Filtrer</button>
        <a href="{{ route('factures.index') }}" class="fct-btn-reset">Reset</a>

        {{-- Previous / Next poussés à droite --}}
        @if ($factures->hasPages())
        <div style="margin-left:auto; display:flex; align-items:center; gap:6px;">
            @if ($factures->onFirstPage())
                <span class="fct-nav-btn fct-nav-disabled">← Précédent</span>
            @else
                <a href="{{ $factures->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="fct-nav-btn">← Précédent</a>
            @endif

            <span style="font-size:11px; color:var(--muted2); white-space:nowrap;">
                Page {{ $factures->currentPage() }} / {{ $factures->lastPage() }}
            </span>

            @if ($factures->hasMorePages())
                <a href="{{ $factures->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="fct-nav-btn">Suivant →</a>
            @else
                <span class="fct-nav-btn fct-nav-disabled">Suivant →</span>
            @endif
        </div>
        @endif
    </form>

    {{-- Compteur --}}
    <div class="fct-count">
        <strong>{{ $factures->total() }}</strong> facture{{ $factures->total() > 1 ? 's' : '' }}
        @if(request('annee')) en <strong>{{ request('annee') }}</strong>@endif
        @if(request('type')) &middot; <strong>{{ request('type') }}</strong>@endif
        @if(request('status')) &middot; <strong>{{ request('status') }}</strong>@endif
    </div>

    {{-- Table --}}
    <div class="fct-table-wrap">
        <table class="fct-table">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Période</th>
                    <th>Type</th>
                    <th>Consommation</th>
                    <th>Montant TTC</th>
                    <th>Échéance</th>
                    <th>Statut</th>
                    <th style="text-align:center">PDF</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($factures as $facture)
                <tr>
                    <td><span class="fct-num">{{ $facture->numero_facture }}</span></td>
                    <td class="fct-muted">{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $facture->annee }}</td>
                    <td>
                        @if ($facture->type === 'eau')
                            <span class="fct-badge fct-badge-eau">💧 Eau</span>
                        @else
                            <span class="fct-badge fct-badge-elec">⚡ Électricité</span>
                        @endif
                    </td>
                    <td class="fct-muted">
                        {{ number_format($facture->consommation, 2) }}
                        <span class="fct-unit">{{ $facture->type === 'eau' ? 'm³' : 'kWh' }}</span>
                    </td>
                    <td>
                        <span class="fct-amt">{{ number_format($facture->montant_ttc, 2) }}</span>
                        <span class="fct-unit">MAD</span>
                    </td>
                    <td class="fct-muted">{{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</td>
                    <td>
                        @if ($facture->status === 'payee')
                            <span class="fct-badge fct-badge-payee">Payée</span>
                        @elseif ($facture->status === 'en_retard')
                            <span class="fct-badge fct-badge-retard">En retard</span>
                        @else
                            <span class="fct-badge fct-badge-impayee">Impayée</span>
                        @endif
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('factures.telecharger', $facture->id) }}" class="fct-dl">
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2v8M5 7l3 3 3-3M3 13h10"/>
                            </svg>
                            PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="fct-empty">Aucune facture trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection