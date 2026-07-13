<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Contrat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlerteController extends Controller
{
               public function alertsIndex(Request $request)
{
    $secteurs = \App\Models\Secteur::orderBy('nom_secteur')->pluck('nom_secteur');

    $query = Alert::with(['contrat', 'technicien'])->latest();

    if (!$request->boolean('all')) {
        $query->whereNotIn('status', ['resolu', 'annule']);
    }

    if ($request->filled('secteur')) {
        $query->whereHas('contrat', fn($q) => $q->where('nom_secteur', $request->secteur));
    }

      $alerts = $query->paginate(10)->withQueryString();

    return view('admin.alerts.index', compact('alerts', 'secteurs'));
}

            public function alertsEdit(Alert $alert)
            {
                $techniciens = User::where('role', 'technicien')->get();
                return view('admin.alerts.edit', compact('alert', 'techniciens'));
            }

                    public function alertsUpdate(Request $request, Alert $alert)
                {
                    $request->validate([
                        'status'   => 'required|in:en_attente,en_cours,resolu,annule',
                        'tech_cin' => 'required|exists:users,cin',
                    ]);

                    DB::table('alerts')
                        ->where('id', $alert->id)
                        ->update([
                            'status'     => $request->status,
                            'tech_cin'   => $request->tech_cin,
                            'updated_at' => now(),
                        ]);

                    return redirect()->route('admin.alerts.index')->with('success', 'Alerte mise à jour.');
                }
                  public function storeAlerte(Request $request)
    {
        $request->validate([
            'contrat_num' => 'required|string',
            'description' => 'required|string|max:1000',
        ]);

        DB::table('alerts')->insert([
            'description' => $request->description,
            'date_alert'  => now()->toDateString(),
            'status'      => 'en_attente',
            'tech_cin'    => Auth::user()->cin,
            'contrat_num' => $request->contrat_num,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return back()->with('success_alert', 'Alerte envoyée avec succès.');
    }
public function alerts()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $contrats = Contrat::where('nom_secteur', $user->nom_secteur)
                       ->pluck('contrat_num');

    $alertes = Alert::where('tech_cin', $user->cin)
                    ->latest('date_alert')
                    ->get();

    return view('technicien.alert', compact('alertes', 'contrats'));
}

}
