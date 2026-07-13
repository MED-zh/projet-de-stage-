<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1a1a2e;
            background: #fff;
        }

        /* ── Header ── */
        .header {
            background: #1a1a2e;
            color: #fff;
            padding: 24px 32px;
            display: table;
            width: 100%;
        }
        .header-left  { display: table-cell; vertical-align: middle; width: 60%; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }

        .logo { font-size: 22px; font-weight: 700; letter-spacing: 1px; }
        .logo span { color: #4fc3f7; }

        .header-right p { font-size: 11px; opacity: .8; }
        .header-right .num {
            font-size: 16px; font-weight: 700;
            color: #4fc3f7; margin-top: 4px;
        }

        /* ── Body padding ── */
        .body { padding: 28px 32px; }

        /* ── Info boxes ── */
        .info-row { display: table; width: 100%; margin-bottom: 24px; }
        .info-box {
            display: table-cell;
            width: 48%;
            background: #f5f7ff;
            border-left: 4px solid #1a1a2e;
            padding: 14px 16px;
            border-radius: 4px;
        }
        .info-box.right { padding-left: 32px; }
        .info-box h4 {
            font-size: 10px; text-transform: uppercase;
            letter-spacing: 1px; color: #888; margin-bottom: 8px;
        }
        .info-box p { font-size: 13px; line-height: 1.7; }
        .info-box .highlight { font-weight: 700; font-size: 15px; }

        /* ── Badge type ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .badge-eau   { background: #e0f7fa; color: #006064; }
        .badge-elec  { background: #fff9e6; color: #b26a00; }

        /* ── Tranches table ── */
        .section-title {
            font-size: 11px; text-transform: uppercase;
            letter-spacing: 1px; color: #888;
            margin-bottom: 10px; margin-top: 20px;
        }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1a1a2e; color: #fff; }
        thead th { padding: 10px 12px; font-size: 11px; text-align: left; }
        tbody tr:nth-child(even) { background: #f5f7ff; }
        tbody td { padding: 9px 12px; font-size: 12px; border-bottom: 1px solid #eee; }
        tfoot td {
            padding: 10px 12px; font-weight: 700;
            border-top: 2px solid #1a1a2e; font-size: 13px;
        }

        /* ── Totals ── */
        .totals {
            margin-top: 20px;
            float: right;
            width: 280px;
        }
        .totals table { border: 1px solid #eee; border-radius: 4px; }
        .totals td { padding: 10px 14px; font-size: 13px; }
        .totals tr:last-child td {
            background: #1a1a2e; color: #fff;
            font-size: 15px; font-weight: 700;
        }

        /* ── Status stamp ── */
        .stamp {
            display: inline-block;
            border: 3px solid;
            border-radius: 6px;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transform: rotate(-8deg);
            margin-top: 8px;
        }
        .stamp-payee    { color: #2e7d32; border-color: #2e7d32; }
        .stamp-impayee  { color: #c62828; border-color: #c62828; }
        .stamp-en_retard{ color: #e65100; border-color: #e65100; }

        /* ── Footer ── */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 12px;
            font-size: 10px;
            color: #aaa;
            text-align: center;
        }

        .clearfix::after { content: ''; display: table; clear: both; }
    </style>
</head>
<body>

{{-- ── HEADER ── --}}
<div class="header">
    <div class="header-left">
        <div class="logo"><span>AquaWatt</span> </div>
        <p style="margin-top:6px; font-size:11px; opacity:.7;">
            Office de l'Électricité et de l'Eau Potable
        </p>
    </div>
    <div class="header-right">
        <p>Facture N°</p>
        <div class="num">{{ $facture->numero_facture }}</div>
        <p style="margin-top:6px;">
            Émise le : {{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}
        </p>
             <p style="margin-top:10px;">
                    <span style="font-size:10px; text-transform:uppercase; letter-spacing:1px; color:#888;">Secteur</span><br>
                    <strong style="font-size:14px;">{{ $contrat->nom_secteur ?? 'N/A' }}</strong>
                </p>
        <p style="margin-top:8px;">
        <span style="font-family:monospace; font-size:13px; font-weight:700; color:#4fc3f7; letter-spacing:2px;">
            {{ strtoupper($contrat->number_secteur ?? 'N/A') }}-{{ str_pad($contrat->ordre_tournee ?? '0', 2, '0', STR_PAD_LEFT) }}
        </span>
      </p>
    </div>
</div>

<div class="body">

    {{-- ── INFO ROW ── --}}
    <div class="info-row">
                <div class="info-box">
                <h4>Client</h4>
                <p><span style="font-size:11px;color:#1a1a2e;font-weight:700;">Nom :</span> {{ $user->name ?? 'N/A' }}</p>
                <p><span style="font-size:11px;color:#1a1a2e;font-weight:700;">Adresse :</span> {{ $user->adresse ?? 'N/A' }}</p>
                <p><span style="font-size:11px;color:#1a1a2e;font-weight:700;">Téléphone :</span> {{ $user->phone ?? 'N/A' }}</p>
                <p >
                    <span style="font-size:11px;color:#1a1a2e;font-weight:700;">Contrat :</span> {{ $facture->contrat_num }}
                </p>
                <p><span style="font-size:11px;color:#1a1a2e;font-weight:700;">Compteur :</span> {{ $releve->matricule }}</p>
             </div>
                        <div class="info-box right">
                <h4>Période &amp; Type</h4>
                <p>
                    <span class="badge {{ $facture->type === 'eau' ? 'badge-eau' : 'badge-elec' }}">
                        {{ $facture->type === 'eau' ? '💧 Eau' : '⚡ Électricité' }}
                    </span>
                </p>
                <p style="margin-top:6px;">
                    Période : {{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $facture->annee }}
                </p>
                <p>Échéance : {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</p>
              
               
            </div>
    </div>

    {{-- ── CONSOMMATION ── --}}
    <p class="section-title">Détail de consommation</p>
    <table>
        <thead>
            <tr>
                <th>Tranche</th>
                <th>Limite min</th>
                <th>Limite max</th>
                <th>Consommation</th>
                <th>Prix unitaire</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @php $restant = $facture->consommation; @endphp
            @foreach ($tranches as $tranche)
                @php
                    if ($restant <= 0) break;
                    $largeur  = $tranche->limite_max - $tranche->limite_min;
                    $conso    = min($restant, $largeur);
                    $montant  = round($conso * $tranche->prix_unitaire, 2);
                    $restant -= $conso;
                    $unite    = $facture->type === 'eau' ? 'm³' : 'kWh';
                @endphp
                <tr>
                    <td>{{ $tranche->label }}</td>
                    <td>{{ $tranche->limite_min }} {{ $unite }}</td>
                    <td>{{ $tranche->limite_max == 9999 ? '∞' : $tranche->limite_max . ' ' . $unite }}</td>
                    <td>{{ number_format($conso, 2) }} {{ $unite }}</td>
                    <td>{{ number_format($tranche->prix_unitaire, 4) }} MAD</td>
                    <td>{{ number_format($montant, 2) }} MAD</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total consommation</td>
                <td>{{ number_format($facture->consommation, 2) }} {{ $facture->type === 'eau' ? 'm³' : 'kWh' }}</td>
                <td></td>
                <td>{{ number_format($facture->montant_ht, 2) }} MAD</td>
            </tr>
        </tfoot>
    </table>

    {{-- ── TOTALS ── --}}
    <div class="totals">
        <table>
            <tr>
                <td>Montant HT</td>
                <td style="text-align:right">{{ number_format($facture->montant_ht, 2) }} MAD</td>
            </tr>
            <tr>
                <td>TVA ({{ $facture->tva }}%)</td>
                <td style="text-align:right">
                    {{ number_format($facture->montant_ttc - $facture->montant_ht, 2) }} MAD
                </td>
            </tr>
            <tr>
                <td>Total TTC</td>
                <td style="text-align:right">{{ number_format($facture->montant_ttc, 2) }} MAD</td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        AquaWatt Maroc — Ce document est généré automatiquement et fait foi de facture officielle.
        En cas de contestation, contactez votre agence locale.
    </div>

</div>
</body>
</html>