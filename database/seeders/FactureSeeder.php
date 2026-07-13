<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FactureSeeder extends Seeder
{
    public function run(): void
    {
        // Charger les tranches depuis la table prix
        $prix = DB::table('prix')->orderBy('type')->orderBy('tranche')->get();

        $tranches = [
            'eau'          => $prix->where('type', 'eau')->values(),
            'electricite'  => $prix->where('type', 'electricite')->values(),
        ];

        // Récupérer les relevés (le type est directement dans releves)
        $releves = DB::table('releves')->get();

        if ($releves->isEmpty()) {
            $this->command->warn('Aucun relevé trouvé. Veuillez exécuter ReleveSeeder en premier.');
            return;
        }

        $factures = [];

        foreach ($releves as $releve) {
            $type = $releve->type; // directement depuis releves
            $mois  = $releve->mois  ?? Carbon::parse($releve->date_releve)->month;
            $annee = $releve->annee ?? Carbon::parse($releve->date_releve)->year;

            // Utiliser la consommation déjà calculée dans le relevé
            $consommation = (float) $releve->consommation;

            // Calcul montant HT par tranches progressives
            $montantHT = $this->calculerMontantHT($consommation, $tranches[$type]);

            $tva        = 20.00;
            $montantTTC = round($montantHT * (1 + $tva / 100), 2);

            $dateEmission = Carbon::create($annee, $mois, 1)->endOfMonth();
            $dateEcheance = $dateEmission->copy()->addDays(30);
            $status       = $this->determineStatus($dateEcheance);

            $factures[] = [
                'numero_facture' => $this->genererNumero($type, $annee, $mois, $releve->contrat_num),
                'mois'           => $mois,
                'annee'          => $annee,
                'type'           => $type,
                'consommation'   => $consommation,
                'montant_ht'     => $montantHT,
                'tva'            => $tva,
                'montant_ttc'    => $montantTTC,
                'status'         => $status,
                'date_emission'  => $dateEmission->toDateString(),
                'date_echeance'  => $dateEcheance->toDateString(),
                'contrat_num'    => $releve->contrat_num,
                'releve_id'      => $releve->id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        foreach (array_chunk($factures, 100) as $chunk) {
            DB::table('factures')->insert($chunk);
        }

        $this->command->info(count($factures) . ' facture(s) créée(s) avec succès.');
    }

    // -------------------------------------------------------------------------
    // Calcul progressif par tranches (comme l'impôt)
    //
    // Exemple eau, consommation = 25 m³ :
    //   Tranche 1 :  0– 6 m³  →  6  × 1.69 =  10.14 MAD
    //   Tranche 2 :  6–20 m³  → 14  × 4.38 =  61.32 MAD
    //   Tranche 3 : 20–25 m³  →  5  × 5.57 =  27.85 MAD
    //   Total HT                             =  99.31 MAD
    // -------------------------------------------------------------------------
    private function calculerMontantHT(float $consommation, $tranches): float
    {
        $total   = 0.0;
        $restant = $consommation;

        foreach ($tranches as $tranche) {
            if ($restant <= 0) break;

            $limiteMin      = (float) $tranche->limite_min;
            $limiteMax      = (float) $tranche->limite_max;
            $prix           = (float) $tranche->prix_unitaire;

            $largeur        = $limiteMax - $limiteMin;       // taille de la tranche
            $consoDansCette = min($restant, $largeur);        // ce qui tombe dans cette tranche

            $total   += $consoDansCette * $prix;
            $restant -= $consoDansCette;
        }

        return round($total, 2);
    }

    // -------------------------------------------------------------------------

    private function determineStatus(Carbon $dateEcheance): string
    {
        if ($dateEcheance->isPast()) {
            return fake()->boolean(40) ? 'payee' : 'en_retard';
        }

        return fake()->boolean(30) ? 'payee' : 'impayee';
    }

    private function genererNumero(string $type, int $annee, int $mois, string $contratNum): string
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $suffix = strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', $contratNum), 0, 6));
        $unique = strtoupper(substr(uniqid(), -4));

        return sprintf('%s-%d%02d-%s-%s', $prefix, $annee, $mois, $suffix, $unique);
        // → EAU-202504-CTR001-A3F2
    }
}