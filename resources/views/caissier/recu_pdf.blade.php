<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
*{box-sizing:border-box;margin:0;padding:0}

html, body{
  width:148mm;
  height:210mm;
  margin:0;
  padding:0;
  font-family:'DejaVu Sans',sans-serif;
  font-size:11px;
  color:#1a1a2e;
  background:#0f1f4b;
}

.page{
  background:#fff;
  width:148mm;
  height:210mm;
  display:flex;
  flex-direction:column;
  overflow:hidden;
}

/* ── Banner ── */
.banner{
  background:#0f1f4b;
  padding:22px 24px 18px;
  position:relative;
  overflow:hidden;
  flex-shrink:0;
}
.banner::before{
  content:'';
  position:absolute;
  top:-50px;right:-50px;
  width:200px;height:200px;
  border-radius:50%;
  background:rgba(255,255,255,0.04);
}
.banner::after{
  content:'';
  position:absolute;
  bottom:-40px;left:40px;
  width:140px;height:140px;
  border-radius:50%;
  background:rgba(0,180,255,0.06);
}

.header-table{
  width:100%;
  border-collapse:collapse;
  position:relative;
  z-index:1;
}
.header-table td{
  vertical-align:middle;
  padding:0;
  border:none;
}

.brand{
  font-size:26px;
  font-weight:700;
  color:#fff;
  letter-spacing:-.5px;
}
.brand span{color:#38bdf8}
.brand-sub{
  font-size:8px;
  color:rgba(255,255,255,0.45);
  margin-top:3px;
  letter-spacing:.12em;
  text-transform:uppercase;
}
.paid-pill{
  display:inline-block;
  background:rgba(56,189,248,0.15);
  border:1px solid rgba(56,189,248,0.30);
  color:#38bdf8;
  font-size:8px;
  font-weight:700;
  letter-spacing:.1em;
  text-transform:uppercase;
  padding:3px 10px;
  border-radius:99px;
  margin-top:10px;
}

.header-right{
  text-align:right;
  vertical-align:middle;
}
.recu-num{
  display:inline-block;
  padding:5px 13px;
  background:rgba(255,255,255,0.10);
  border:1px solid rgba(255,255,255,0.14);
  border-radius:20px;
  color:#fff;
  font-size:9px;
  font-weight:700;
  font-family:monospace;
  letter-spacing:.06em;
  margin-bottom:8px;
}
.recu-date{
  display:inline-block;
  padding:6px 14px;
  background:#38bdf8;
  border-radius:22px;
  color:#0f1f4b;
  font-size:9px;
  font-weight:700;
  letter-spacing:.06em;
  text-transform:uppercase;
}

/* ── Body ── */
.body{
  padding:18px 24px;
  flex:1;              /* ← fills all remaining vertical space */
  background:#fff;
  display:flex;
  flex-direction:column;
}

.grid2{display:flex;gap:10px;margin-bottom:16px}
.info-card{
  flex:1;
  background:#f7f9ff;
  border:1px solid #e2eaff;
  border-radius:8px;
  padding:10px 12px;
}
.section-label{
  font-size:7px;
  text-transform:uppercase;
  letter-spacing:.1em;
  color:#8899bb;
  margin-bottom:4px;
  font-weight:700;
}
.val{font-size:11px;font-weight:700;color:#111a33}
.sub{font-size:9px;color:#6677aa;margin-top:2px}

/* ── Table ── */
.table-wrap{
  border-radius:8px;
  overflow:hidden;
  border:1px solid #e2eaff;
  margin-bottom:16px;
}
table.data-table{width:100%;border-collapse:collapse}
table.data-table thead th{
  background:#f0f4ff;
  padding:7px 10px;
  text-align:left;
  font-size:7px;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:#6677aa;
  font-weight:700;
  border-bottom:1px solid #e2eaff;
}
table.data-table tbody td{
  padding:8px 10px;
  font-size:10px;
  border-bottom:1px solid #f0f4ff;
  color:#222;
}
table.data-table tbody tr:last-child td{border-bottom:none}
table.data-table tbody tr:nth-child(even) td{background:#fafbff}
.right{text-align:right}

.badge-eau{background:#dbeafe;color:#1a4fa8;padding:2px 6px;border-radius:99px;font-size:7px;font-weight:700}
.badge-elec{background:#fef3c7;color:#854d0e;padding:2px 6px;border-radius:99px;font-size:7px;font-weight:700}

/* ── Spacer — pushes total+notes to bottom ── */
.spacer{flex:1}

/* ── Total ── */
.total-box{
  background:#0f1f4b;
  border-radius:10px;
  padding:14px 18px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:14px;
}
.total-label{font-size:7px;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,0.45);margin-bottom:3px}
.total-val{font-size:20px;font-weight:700;color:#fff}
.total-sub{font-size:8px;color:rgba(255,255,255,0.4);margin-top:2px}
.check-circle{
  width:36px;height:36px;border-radius:50%;
  background:rgba(56,189,248,0.15);
  border:2px solid rgba(56,189,248,0.35);
  display:flex;align-items:center;justify-content:center;
  font-size:15px;color:#38bdf8;
}

.notes-box{
  background:#f7f9ff;
  border-left:3px solid #0f1f4b;
  border-radius:0 6px 6px 0;
  padding:8px 12px;
  font-size:9px;
  color:#445;
  margin-bottom:14px;
}

/* ── Footer ── */
.footer{
  background:#0f1f4b;
  padding:10px 24px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-shrink:0;
}
.footer-brand{font-size:11px;font-weight:700;color:#fff}
.footer-brand span{color:#38bdf8}
.footer-meta{font-size:7px;color:rgba(255,255,255,0.35)}
</style>
</head>
<body>

<div class="page">

  {{-- ── Banner ── --}}
  <div class="banner">
    <table class="header-table">
      <tr>
        <td>
          <div class="brand">Aqua<span>Watt</span></div>
          <div class="brand-sub">Reçu officiel de paiement</div>
          <div class="paid-pill">✓ Payé</div>
        </td>
        <td class="header-right">
          <div class="recu-num">{{ $numero }}</div>
          <br>
          <div class="recu-date">
            {{ \Carbon\Carbon::parse($paiements->first()->date_paiement)->translatedFormat('d M Y') }}
          </div>
        </td>
      </tr>
    </table>
  </div>

  {{-- ── Body ── --}}
  <div class="body">

    <div class="grid2">
      <div class="info-card">
        <div class="section-label">Client</div>
        <div class="val">{{ $client->name ?? '—' }}</div>
        <div class="sub">{{ $client->adresse ?? '' }}</div>
        <div class="sub">Contrat : {{ $paiements->first()->contrat_num }}</div>
      </div>
         <div class="info-card">
            <div class="section-label">Secteur</div>
            <div class="val">{{ $contrat->nom_secteur ?? '—' }}</div>
            <div class="sub">Ordre_tournee: {{ $contrat->ordre_tournee ?? '—' }}</div>
        </div>
      <div class="info-card">
        <div class="section-label">Encaissé par</div>
        <div class="val">{{ $caissier->name ?? '—' }}</div>
        <div class="sub">Caissier</div>
      </div>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>N° Facture</th>
            <th>Type</th>
            <th>Période</th>
            <th class="right">Montant TTC</th>
          </tr>
        </thead>
        <tbody>
          @foreach($paiements as $p)
          <tr>
            <td style="font-family:monospace;color:#0f1f4b;font-weight:700">{{ $p->facture->numero_facture ?? '—' }}</td>
            <td>
              @if(($p->facture->type ?? '') === 'eau')
                <span class="badge-eau">Eau</span>
              @else
                <span class="badge-elec">Elec</span>
              @endif
            </td>
            <td style="color:#6677aa">
              @if($p->facture)
                {{ \Carbon\Carbon::create($p->facture->annee, $p->facture->mois)->translatedFormat('F Y') }}
              @else —
              @endif
            </td>
            <td class="right" style="font-weight:700;color:#111a33">
              {{ number_format($p->montant_total, 2, ',', ' ') }}
              <span style="color:#99aacc;font-size:8px">DH</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- spacer pushes total box down to bottom --}}
    <div class="spacer"></div>

    @if($paiements->first()->notes)
    <div class="notes-box"><strong>Notes :</strong> {{ $paiements->first()->notes }}</div>
    @endif

    <div class="total-box">
      <div>
        <div class="total-label">Total encaissé</div>
        <div class="total-val">{{ number_format($montantTotal, 2, ',', ' ') }} DH</div>
        <div class="total-sub">{{ $paiements->count() }} facture{{ $paiements->count() > 1 ? 's' : '' }} réglée{{ $paiements->count() > 1 ? 's' : '' }}</div>
      </div>
      <div class="check-circle">✓</div>
    </div>

  </div>

  {{-- ── Footer ── --}}
  <div class="footer">
    <div class="footer-brand">Aqua<span>Watt</span></div>
    <div class="footer-meta">Généré le {{ now()->format('d/m/Y à H:i') }} — Document officiel</div>
  </div>

</div>

</body>
</html>