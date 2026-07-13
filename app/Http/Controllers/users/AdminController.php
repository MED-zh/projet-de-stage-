<?php

namespace App\Http\Controllers\Users; 

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Contrat;
use App\Models\Releve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ================= DASHBOARD =================
public function dashboard(Request $request)
{
    $secteurs        = \App\Models\Secteur::orderBy('nom_secteur')->pluck('nom_secteur');
    $selectedSecteur = $request->get('secteur', null);
    $selectedContrat = $request->get('contrat', null);

    // Contrats list depends on selected secteur
    $contratsQuery = \App\Models\Contrat::query();
    if ($selectedSecteur) {
        $contratsQuery->where('nom_secteur', $selectedSecteur);
    }
    $contrats = $contratsQuery->orderBy('contrat_num')->pluck('contrat_num');

    $eauQuery  = Releve::where('type', 'eau')->whereYear('date_releve', now()->year);
    $elecQuery = Releve::where('type', 'electricite')->whereYear('date_releve', now()->year);

    if ($selectedContrat) {
        $eauQuery->where('contrat_num', $selectedContrat);
        $elecQuery->where('contrat_num', $selectedContrat);
    } elseif ($selectedSecteur) {
        $secteurContrats = \App\Models\Contrat::where('nom_secteur', $selectedSecteur)->pluck('contrat_num');
        $eauQuery->whereIn('contrat_num', $secteurContrats);
        $elecQuery->whereIn('contrat_num', $secteurContrats);
    }

    $totalEau  = (clone $eauQuery)->sum('consommation');
    $totalElec = (clone $elecQuery)->sum('consommation');

    $eauByMonth = (clone $eauQuery)
        ->selectRaw('EXTRACT(MONTH FROM date_releve) as month, SUM(consommation) as total')
        ->groupBy(DB::raw('EXTRACT(MONTH FROM date_releve)'))
        ->orderBy(DB::raw('EXTRACT(MONTH FROM date_releve)'))
        ->get();

    $elecByMonth = (clone $elecQuery)
        ->selectRaw('EXTRACT(MONTH FROM date_releve) as month, SUM(consommation) as total')
        ->groupBy(DB::raw('EXTRACT(MONTH FROM date_releve)'))
        ->orderBy(DB::raw('EXTRACT(MONTH FROM date_releve)'))
        ->get();

    $topConsumers = (clone $eauQuery)
        ->selectRaw('contrat_num, SUM(consommation) as total')
        ->groupBy('contrat_num')->orderByDesc('total')->take(5)->get();

    $topConsumersElec = (clone $elecQuery)
        ->selectRaw('contrat_num, SUM(consommation) as total')
        ->groupBy('contrat_num')->orderByDesc('total')->take(5)->get();

    $totalUsers       = User::where('role', 'client')->count();
    $totalTechniciens = User::where('role', 'technicien')->count();
    $totalContrats    = $selectedContrat
        ? 1
        : ($selectedSecteur
            ? \App\Models\Contrat::where('nom_secteur', $selectedSecteur)->count()
            : \App\Models\Contrat::count());

    return view('admin.dashboard', compact(
        'totalEau', 'totalElec',
        'eauByMonth', 'elecByMonth',
        'topConsumers', 'topConsumersElec',
        'totalUsers', 'totalTechniciens', 'totalContrats',
        'secteurs', 'selectedSecteur',
        'contrats', 'selectedContrat'
    ));
}

    public function consommation(Request $request)
{
    $selectedYear    = $request->get('year', now()->year);
    $selectedType    = $request->get('type', 'eau');
    $selectedSecteur = $request->get('secteur', null);

    $secteurs = \App\Models\Secteur::orderBy('nom_secteur')->pluck('nom_secteur');

    $years = Releve::selectRaw('EXTRACT(YEAR FROM date_releve) as year')
        ->groupByRaw('EXTRACT(YEAR FROM date_releve)')
        ->orderBy('year', 'asc')
        ->pluck('year');

    $eauByMonth  = array_fill(0, 12, 0);
    $elecByMonth = array_fill(0, 12, 0);

    $query = Releve::whereRaw('EXTRACT(YEAR FROM date_releve) = ?', [$selectedYear]);

    if ($selectedSecteur) {
        $contrats = \App\Models\Contrat::where('nom_secteur', $selectedSecteur)->pluck('contrat_num');
        $query->whereIn('contrat_num', $contrats);
    }

    $query->selectRaw('EXTRACT(MONTH FROM date_releve) as month, type, SUM(consommation) as total')
        ->groupByRaw('EXTRACT(MONTH FROM date_releve), type')
        ->get()
        ->each(function ($r) use (&$eauByMonth, &$elecByMonth) {
            $monthNum = $r->month ?? $r->MONTH;
            $type     = $r->type  ?? $r->TYPE;
            $total    = $r->total ?? $r->TOTAL;
            $index    = (int)$monthNum - 1;
            if ($index >= 0 && $index < 12) {
                if ($type === 'eau')         $eauByMonth[$index]  = $total;
                elseif ($type === 'electricite') $elecByMonth[$index] = $total;
            }
        });

    return view('admin.consommation', compact(
        'selectedYear', 'selectedType', 'selectedSecteur',
        'years', 'eauByMonth', 'elecByMonth',
        'secteurs'
    ));
}}