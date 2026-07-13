<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    // ── Liste des factures de l'utilisateur connecté ──────────────────────────
   public function index(Request $request)
{
    $contratNum = Auth::user()->contrat_num;

    if (!$contratNum) {
        return view('factures.index', ['factures' => collect()->paginate(10)]);
    }

    $query = DB::table('factures')
        ->join('releves', 'factures.releve_id', '=', 'releves.id')
        ->select('factures.*', 'releves.matricule')
        ->where('factures.contrat_num', $contratNum)  // ← filtre par user
        ->orderByDesc('factures.date_emission');

    if ($request->filled('type')) {
        $query->where('factures.type', $request->type);
    }
    if ($request->filled('status')) {
        $query->where('factures.status', $request->status);
    }
    if ($request->filled('annee')) {
        $query->where('factures.annee', $request->annee);
    }
      $years = DB::table('factures')
        ->where('contrat_num', $contratNum)
        ->distinct()
        ->orderByDesc('annee')
        ->pluck('annee');

    $factures = $query->paginate(10)->withQueryString();

    return view('factures.index', compact('factures', 'years'));
}
    // ── Téléchargement PDF ─────────────────────────────────────────────────────
    public function telecharger($id)
    {
        // Récupérer la facture
        $facture = DB::table('factures')->where('id', $id)->firstOrFail();
                $user = DB::table('users')
            ->where('contrat_num', $facture->contrat_num)
            ->select('name', 'phone', 'adresse')
            ->first();

        // Récupérer le relevé lié
        $releve = DB::table('releves')->where('id', $facture->releve_id)->firstOrFail();

        // Récupérer les infos contrat/client
                $contrat = DB::table('contrats')
            ->join('secteurs', 'contrats.nom_secteur', '=', 'secteurs.nom_secteur')
            ->where('contrats.contrat_num', $facture->contrat_num)
            ->select('contrats.nom_secteur', 'contrats.ordre_tournee', 'contrats.adresse', 'secteurs.number_secteur')
            ->first();

        // Tranches de prix pour le type concerné
        $tranches = DB::table('prix')
            ->where('type', $facture->type)
            ->orderBy('tranche')
            ->get();

      return Pdf::loadView('factures.facture_pdf', compact('facture', 'releve', 'contrat', 'tranches', 'user'))
          ->setPaper('a4', 'portrait')
          ->download('Facture_' . $facture->numero_facture . '.pdf');
    }
}