@extends('technicien.layout')
@section('title', 'Relevés')
@section('content')
<style>
.t*{box-sizing:border-box}
.page-title{font-family:var(--display);font-size:20px;font-weight:700;color:var(--white);letter-spacing:-.5px;margin-bottom:4px}
.page-sub{font-size:13px;color:var(--muted);margin-bottom:1.5rem}

/* Alerts */
.t-alert{padding:10px 16px;border-radius:8px;font-size:13px;margin-bottom:1rem;display:flex;align-items:center;gap:8px}
.t-alert-success{background:var(--green-lo);border:1px solid rgba(74,222,128,.2);color:var(--green)}
.t-alert-error{background:var(--red-lo);border:1px solid rgba(248,113,113,.2);color:var(--red)}

/* Secteur info */
.secteur-bar{display:flex;align-items:center;gap:10px;margin-bottom:1.5rem;padding:12px 16px;border:1px solid var(--border);border-radius:var(--r);background:var(--s2)}
.secteur-label{font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2)}
.secteur-val{font-family:var(--display);font-size:14px;font-weight:700;color:var(--cyan)}

/* Contrats list */
.contrats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px;margin-bottom:1.5rem}
.contrat-item{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1rem;cursor:pointer;transition:border-color .2s,background .2s}
.contrat-item:hover{border-color:var(--border2);background:var(--s3)}
.contrat-item.selected{border-color:var(--blue);background:var(--blue-lo)}
.contrat-num{font-family:var(--display);font-size:13px;font-weight:700;color:var(--blue-hi);margin-bottom:4px}
.contrat-meta{font-size:11px;color:var(--muted)}

/* Modal overlay */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(4,6,15,.8);backdrop-filter:blur(6px);z-index:100;align-items:center;justify-content:center}
.modal-overlay.open{display:flex}
.modal{background:var(--s2);border:1px solid var(--border2);border-radius:16px;width:100%;max-width:560px;max-height:90vh;overflow-y:auto;padding:1.75rem;position:relative}
.modal-close{position:absolute;top:1rem;right:1rem;background:transparent;border:none;color:var(--muted);cursor:pointer;font-size:18px;line-height:1}
.modal-close:hover{color:var(--text)}
.modal-title{font-family:var(--display);font-size:16px;font-weight:700;color:var(--white);margin-bottom:1.25rem}

/* Info grid inside modal */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:1.25rem}
.info-cell{background:var(--s3);border-radius:8px;padding:10px 12px}
.info-cell-label{font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);margin-bottom:3px}
.info-cell-val{font-size:13px;color:var(--text);font-weight:500}

/* Compteur block */
.compteur-block{border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:.75rem;background:var(--s3)}
.compteur-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
.compteur-mat{font-family:var(--display);font-size:13px;font-weight:700;color:var(--blue-hi)}
.type-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:600}
.type-eau{background:rgba(0,207,255,.1);color:#00cfff;border:1px solid rgba(0,207,255,.2)}
.type-elec{background:rgba(61,127,255,.12);color:#6699ff;border:1px solid rgba(61,127,255,.2)}
.index-current{font-size:11px;color:var(--muted2);margin-bottom:.75rem}
.index-current strong{color:var(--cyan);font-family:var(--display);font-size:14px}

/* Form inside modal */
.form-row{display:flex;gap:8px;align-items:flex-end}
.form-group{display:flex;flex-direction:column;gap:4px;flex:1}
.form-label{font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);font-weight:600}
.form-input{height:34px;padding:0 12px;border-radius:8px;font-size:13px;background:var(--s2);border:1px solid var(--border2);color:var(--text);font-family:var(--body);outline:none;width:100%}
.form-input:focus{border-color:var(--blue)}
.btn-save{height:34px;padding:0 16px;border-radius:8px;font-size:12px;font-weight:600;background:var(--blue);border:none;color:#fff;cursor:pointer;font-family:var(--body);white-space:nowrap}
.btn-save:hover{opacity:.88}

.divider{height:1px;background:var(--border);margin:1.25rem 0}
</style>

<div>
    <div class="page-title">Saisie des Relevés</div>
    <div class="page-sub">Contrats de votre secteur — cliquez pour saisir un relevé</div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="t-alert t-alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="t-alert t-alert-error">⚠ {{ session('error') }}</div>
    @endif

    {{-- Secteur info --}}
    <div class="secteur-bar">
        <div>
            <div class="secteur-label">Votre secteur</div>
            <div class="secteur-val">{{ $secteur->number_secteur ?? 'N/A' }} — {{ Auth::user()->nom_secteur }}</div>
        </div>
        <div style="margin-left:auto;font-size:12px;color:var(--muted)">
            <strong style="color:var(--white)">{{ $contrats->count() }}</strong> contrat(s)
        </div>
    </div>

    {{-- Liste des contrats --}}
    <div class="contrats-grid">
        @forelse ($contrats as $contrat)
        <div class="contrat-item" onclick="openModal({{ json_encode($contrat) }}, {{ json_encode($contrat->compteurs) }}, {{ json_encode($contrat->client) }})">
            <div class="contrat-num">{{ $contrat->contrat_num }}</div>
            <div class="contrat-meta">{{ $contrat->adresse }}</div>
            <div class="contrat-meta" style="margin-top:4px">
                {{ $contrat->compteurs->count() }} compteur(s) ·
                <span style="color:{{ $contrat->status === 'actif' ? 'var(--green)' : 'var(--amber)' }}">
                    {{ ucfirst($contrat->status) }}
                </span>
            </div>
        </div>
        @empty
        <p style="color:var(--muted2);font-size:13px">Aucun contrat dans votre secteur.</p>
        @endforelse
    </div>
</div>

{{-- Modal --}}
<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
    <div class="modal" id="modal">
        <button class="modal-close" onclick="closeModal()">✕</button>
        <div class="modal-title" id="modalTitle">Relevé — </div>

        {{-- Info client + contrat --}}
        <div class="info-grid" id="modalInfo"></div>

        <div class="divider"></div>

        {{-- Compteurs --}}
        <div id="modalCompteurs"></div>
    </div>
</div>

@push('scripts')
<script>
function openModal(contrat, compteurs, client) {
    // Title
    document.getElementById('modalTitle').textContent = 'Relevé — ' + contrat.contrat_num;

    // Info grid
    document.getElementById('modalInfo').innerHTML = `
        <div class="info-cell"><div class="info-cell-label">Client</div><div class="info-cell-val">${client?.name ?? 'N/A'}</div></div>
        <div class="info-cell"><div class="info-cell-label">Téléphone</div><div class="info-cell-val">${client?.phone ?? 'N/A'}</div></div>
        <div class="info-cell"><div class="info-cell-label">Adresse</div><div class="info-cell-val">${contrat.adresse}</div></div>
        <div class="info-cell"><div class="info-cell-label">Tournée</div><div class="info-cell-val">${contrat.ordre_tournee}</div></div>
    `;

    // Compteurs
    let html = '';
    if (!compteurs || compteurs.length === 0) {
        html = '<p style="color:var(--muted2);font-size:13px">Aucun compteur trouvé.</p>';
    } else {
        compteurs.forEach(c => {
            const unite = c.type === 'eau' ? 'm³' : 'kWh';
            const badgeClass = c.type === 'eau' ? 'type-eau' : 'type-elec';
            const icon = c.type === 'eau' ? '💧' : '⚡';
            html += `
            <div class="compteur-block">
                <div class="compteur-head">
                    <span class="compteur-mat">${c.matricule}</span>
                    <span class="type-badge ${badgeClass}">${icon} ${c.type === 'eau' ? 'Eau' : 'Électricité'}</span>
                </div>
                <div class="index-current">Index départ : <strong>${c.dernier_index}</strong> ${unite}</div>
                <form method="POST" action="{{ route('tech.releve.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="contrat_num" value="${contrat.contrat_num}">
                    <input type="hidden" name="matricule"   value="${c.matricule}">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Index fin (${unite})</label>
                            <input type="number" name="index_fin" class="form-input"
                                   min="${c.dernier_index}" step="0.01" placeholder="${c.dernier_index}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date relevé</label>
                            <input type="date" name="date_releve" class="form-input"
                                   value="${new Date().toISOString().split('T')[0]}" required>
                        </div>
                        <button type="submit" class="btn-save">Enregistrer</button>
                    </div>
                </form>
            </div>`;
        });
    }
    document.getElementById('modalCompteurs').innerHTML = html;

    document.getElementById('modalOverlay').classList.add('open');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
}

function closeOnOverlay(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}
</script>
@endpush
@endsection