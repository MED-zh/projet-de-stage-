<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReleveController extends Controller
{
   public function storeReleve(Request $request)
    {
        $request->validate([
            'contrat_num'  => 'required|string',
            'matricule'    => 'required|string',
            'index_fin'    => 'required|numeric|min:0',
            'date_releve'  => 'required|date',
        ]);

        $compteur = DB::table('compteurs')
            ->where('matricule', $request->matricule)
            ->first();

        if (!$compteur) {
            return back()->with('error', 'Compteur introuvable.')->withInput();
        }

        $indexDepart  = $compteur->dernier_index;
        $indexFin     = (float) $request->index_fin;

        if ($indexFin < $indexDepart) {
            return back()->with('error', "L'index fin ({$indexFin}) ne peut pas être inférieur à l'index départ ({$indexDepart}).")->withInput();
        }

        $consommation = $indexFin - $indexDepart;

        // Insérer le relevé
        $releveId = DB::table('releves')->insertGetId([
            'date_releve'  => $request->date_releve,
            'type'         => $compteur->type,
            'index_depart' => $indexDepart,
            'index_fin'    => $indexFin,
            'consommation' => $consommation,
            'matricule'    => $request->matricule,
            'contrat_num'  => $request->contrat_num,
            'tech_cin'     => Auth::user()->cin,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Mettre à jour dernier_index du compteur
        DB::table('compteurs')
            ->where('matricule', $request->matricule)
            ->update(['dernier_index' => $indexFin, 'updated_at' => now()]);

        // Générer la facture automatiquement
        $this->genererFacture($request->contrat_num, $releveId, $compteur, $consommation, $request->date_releve);

        return back()->with('success', "Relevé enregistré avec succès. Consommation : {$consommation} " . ($compteur->type === 'eau' ? 'm³' : 'kWh'));
    }
     private function genererFacture(string $contratNum, int $releveId, object $compteur, float $consommation, string $dateReleve): void
    {
        $tranches = DB::table('prix')
            ->where('type', $compteur->type)
            ->orderBy('tranche')
            ->get();

        $montantHT = 0.0;
        $restant   = $consommation;

        foreach ($tranches as $tranche) {
            if ($restant <= 0) break;
            $largeur        = $tranche->limite_max - $tranche->limite_min;
            $consoDansCette = min($restant, $largeur);
            $montantHT     += $consoDansCette * $tranche->prix_unitaire;
            $restant        -= $consoDansCette;
        }

        $montantHT  = round($montantHT, 2);
        $tva        = 20.00;
        $montantTTC = round($montantHT * 1.20, 2);

        $date         = Carbon::parse($dateReleve);
        $dateEmission = $date->copy()->endOfMonth();
        $dateEcheance = $dateEmission->copy()->addDays(30);

        $prefix  = strtoupper(substr($compteur->type, 0, 3));
        $suffix  = strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', $contratNum), 0, 6));
        $unique  = strtoupper(substr(uniqid(), -4));
        $numero  = sprintf('%s-%d%02d-%s-%s', $prefix, $date->year, $date->month, $suffix, $unique);

        DB::table('factures')->insert([
            'numero_facture' => $numero,
            'mois'           => $date->month,
            'annee'          => $date->year,
            'type'           => $compteur->type,
            'consommation'   => $consommation,
            'montant_ht'     => $montantHT,
            'tva'            => $tva,
            'montant_ttc'    => $montantTTC,
            'status'         => 'impayee',
            'date_emission'  => $dateEmission->toDateString(),
            'date_echeance'  => $dateEcheance->toDateString(),
            'contrat_num'    => $contratNum,
            'releve_id'      => $releveId,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

}