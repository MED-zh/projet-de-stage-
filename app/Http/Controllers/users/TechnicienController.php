<?php

namespace App\Http\Controllers\Users; 

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TechnicienController extends Controller
{
     // ================= TECHNICIENS =================
     public function techniciensIndex(Request $request)
{
    $secteurs = DB::table('secteurs')->orderBy('nom_secteur')->pluck('nom_secteur');

    if ($request->boolean('all')) {
        $techniciens = User::where('role', 'technicien')->latest()->get();
        $selectedSecteur = null;
    } else {
        $selectedSecteur = $request->get('secteur', $secteurs->first());
        $techniciens = User::where('role', 'technicien')
            ->where('nom_secteur', $selectedSecteur)
            ->latest()
            ->get();
    }

    return view('admin.techniciens.index', compact('techniciens', 'secteurs', 'selectedSecteur'));
}

            public function techniciensCreate()
            {
                $secteurs = DB::table('secteurs')->orderBy('nom_secteur')->pluck('nom_secteur');
                return view('admin.techniciens.create', compact('secteurs'));
            }

    public function techniciensStore(Request $request)
    {
        $request->validate([
        'name'     => 'required|string|max:255',
        'nom_secteur'   => 'required|exists:secteurs,nom_secteur',
        'email'    => 'required|email|unique:users',
        'phone'    => 'nullable|string',
        'adresse'  => 'nullable|string',
        'cin'      => 'string',
        'password' => 'required|min:8|confirmed',
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'nom_secteur'   => $request->nom_secteur,
        'adresse'  => $request->adresse,
        'cin'      => $request->cin,
        'password' => Hash::make($request->password),
        'role'     => 'technicien',
    ]);

    return redirect()->route('admin.techniciens.index')
        ->with('success', 'Technicien créé avec succès');
    }

    public function techniciensEdit($id)
    {
        $technicien = User::where('role', 'technicien')->findOrFail($id);
       $secteurs = DB::table('secteurs')->orderBy('nom_secteur')->pluck('nom_secteur');
        return view('admin.techniciens.edit', compact('technicien','secteurs'));
    }

         public function techniciensUpdate(Request $request, $id)
            {
                $technicien = User::where('role', 'technicien')
                    ->where('id', $id)
                    ->firstOrFail();

                $request->validate([
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|email|unique:users,email,' . $id,
                    'phone'    => 'nullable|string',
                    'nom_secteur'   => 'required|exists:secteurs,nom_secteur',
                    'adresse'  => 'nullable|string',
                    'cin'      => 'nullable|string',
                    'password' => 'nullable|min:8|confirmed',
                ]);

                // Update user data
                $technicien->update([
                    'name'    => $request->name,
                    'email'   => $request->email,
                    'nom_secteur'   => $request->nom_secteur,
                    'phone'   => $request->phone,
                    'adresse' => $request->adresse,
                    'cin'     => $request->cin,
                ]);

                // Update password only if filled
                if ($request->filled('password')) {
                    $technicien->update([
                        'password' => Hash::make($request->password)
                    ]);
                }

                return redirect()->route('admin.techniciens.index')
                    ->with('success', 'Technicien mis à jour avec succès');
            }
    public function techniciensDestroy($id)
    {
        User::where('role', 'technicien')->findOrFail($id)->delete();
        return redirect()->route('admin.techniciens.index')
                         ->with('success', 'Technicien supprimé');
    }
     public function recherche(Request $request)
    {
        $request->validate(['contrat_num' => 'required|string']);

        $contrat = DB::table('contrats')
            ->where('contrat_num', $request->contrat_num)
            ->first();

        if (!$contrat) {
            return back()->with('error', 'Contrat introuvable.')->withInput();
        }

        $client = DB::table('users')
            ->where('contrat_num', $request->contrat_num)
            ->select('name', 'phone', 'adresse', 'cin')
            ->first();

        $compteurs = DB::table('compteurs')
            ->where('contrat_num', $request->contrat_num)
            ->get();

        return view('technicien.index', compact('contrat', 'client', 'compteurs'));
    }
public function affiche()
{
    $user    = Auth::user();
    $secteur = DB::table('secteurs')->where('nom_secteur', $user->nom_secteur)->first();

    $contrats = DB::table('contrats')
        ->where('nom_secteur', $user->nom_secteur)
        ->orderBy('ordre_tournee')
        ->get()
        ->map(function ($contrat) {
            $contrat->compteurs = \App\Models\Compteur::where('contrat_num', $contrat->contrat_num)
                ->get()
                ->map(function ($compteur) {
                    $dernier = \App\Models\Releve::where('matricule', $compteur->matricule)
                        ->orderByDesc('date_releve')
                        ->first();

                    $compteur->dernier_index = $dernier?->index_fin ?? 0;
                    $compteur->date_dernier  = $dernier?->date_releve ?? null;

                    return $compteur;
                });

            $contrat->client = DB::table('users')
                ->where('contrat_num', $contrat->contrat_num)
                ->select('name', 'phone', 'adresse')
                ->first();

            return $contrat;
        });

    return view('technicien.index', compact('secteur', 'contrats'));
}


}
