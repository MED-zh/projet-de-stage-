<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratController extends Controller
{
    // ================= CONTRATS =================
                public function contratsIndex(Request $request)
                {
                    $secteurs = DB::table('secteurs')->orderBy('nom_secteur')->pluck('nom_secteur');

                    $selectedSecteur = $request->get('secteur', $secteurs->first()); // default = first secteur

                    $contrats = Contrat::with('utilisateur')
                        ->where('nom_secteur', $selectedSecteur)
                        ->latest()
                        ->paginate(25)
                        ->withQueryString();

                    return view('admin.contrats.index', compact('contrats', 'secteurs', 'selectedSecteur'));
                }

                public function contratsCreate()
                {
                    do {
                        $contratNum = 'SRM-' . now()->format('Ymd') . '-' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
                    } while (Contrat::where('contrat_num', $contratNum)->exists());
                       $secteurs = DB::table('secteurs')->select('nom_secteur', 'number_secteur')->get(); // 👈

                   return view('admin.contrats.create', compact('contratNum', 'secteurs'));
                    
                }

      public function contratsStore(Request $request)
            {
                $request->validate([
                    'contrat_num' => 'required|unique:contrats,contrat_num',
                    'date_fin'    => 'required|date|after:date_debut',
                     'adresse'       => 'required|string',
                    'nom_secteur'   => 'required|exists:secteurs,nom_secteur',
                     'ordre_tournee' => 'required|integer|min:1',
                    'statut'      => 'required|in:actif,inactif',
                ]);

               $exists = DB::table('contrats')
            ->where('nom_secteur', $request->nom_secteur)
            ->where('ordre_tournee', $request->ordre_tournee)
            ->exists();

        if ($exists) {
    return redirect()->back()
        ->withErrors(['ordre_tournee' => 'Cet ordre de tournée existe déjà dans ce secteur.'])
        ->withInput();
}

        

        DB::table('contrats')->insert([
            'contrat_num'   => $request->contrat_num,
            'adresse'       => $request->adresse,
            'nom_secteur'   => $request->nom_secteur,
            'ordre_tournee' => $request->ordre_tournee,
            'date_debut'    => now(),  // 👈 automatic
            'date_fin'      => null,
            'status'        => 'actif',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

     return redirect()->route('admin.contrats.index')
                         ->with('success', 'crier avec succe');
     }

    public function contratsEdit($id)
    {
        $contrat = Contrat::findOrFail($id);
        $users   = User::where('role', 'client')->get();
         $secteurs = DB::table('secteurs')->select('nom_secteur', 'number_secteur')->get();
        return view('admin.contrats.edit', compact('contrat', 'users','secteurs'));
    }

   public function contratsUpdate(Request $request, $id)
{
    $request->validate([
        'adresse'       => 'required|string',
        'nom_secteur'   => 'required|exists:secteurs,nom_secteur',
        'ordre_tournee' => 'required|integer|min:1',
        'status'        => 'required|in:actif,suspendu,resilie',
        'date_fin'      => 'nullable|date',
    ]);

    // Vérifier si l'ordre de tournée existe déjà dans ce secteur 
    // MAIS ignorer le contrat actuel ($id)
    $exists = DB::table('contrats')
        ->where('nom_secteur', $request->nom_secteur)
        ->where('ordre_tournee', $request->ordre_tournee)
        ->where('id', '!=', $id) // 👈 Important : ne pas se bloquer soi-même
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['ordre_tournee' => 'Cet ordre de tournée existe déjà dans ce secteur.'])
            ->withInput();
    }

    // Mise à jour de la table
    DB::table('contrats')->where('id', $id)->update([
        'adresse'       => $request->adresse,
        'nom_secteur'   => $request->nom_secteur,
        'ordre_tournee' => $request->ordre_tournee,
        'status'        => $request->status, // Vérifiez si votre colonne est 'status' ou 'statut'
        'date_fin'      => $request->date_fin,
        'updated_at'    => now(),
    ]);

    // Redirection classique pour une interface Blade
    return redirect()
        ->route('admin.contrats.index')
        ->with('success', 'Le contrat a été mis à jour avec succès.');
        
    /* 
    Si vous utilisez de l'AJAX, gardez le JSON :
    return response()->json(['message' => 'Contrat mis à jour.'], 200); 
    */
}

    public function contratsDestroy($id)
    {
        Contrat::findOrFail($id)->delete();
        return redirect()->route('admin.contrats.index')
                         ->with('success', 'Contrat supprimé');
    }
}
