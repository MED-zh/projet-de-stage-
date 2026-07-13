<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CompteurController extends Controller
{
    public function create()
    {
        $secteurs = DB::table('secteurs')->orderBy('nom_secteur')->get();

        // Contrats that are missing at least one type (eau OR electricite)
        $contratsDisponibles = DB::table('contrats')
            ->whereRaw("
                (
                    SELECT COUNT(DISTINCT type) FROM compteurs
                    WHERE compteurs.contrat_num = contrats.contrat_num
                ) < 2
            ")
            ->orderBy('contrat_num')
            ->get(['contrat_num', 'adresse', 'nom_secteur']);

        return view('admin.compteur.create', compact('secteurs', 'contratsDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contrat_num' => 'required|exists:contrats,contrat_num',
            'matricule'   => 'required|string|unique:compteurs,matricule',
            'type'        => 'required|in:eau,electricite',
        ]);

        // Check: this exact type doesn't already exist for this contrat
        $alreadyExists = DB::table('compteurs')
            ->where('contrat_num', $request->contrat_num)
            ->where('type', $request->type)
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()
                ->withErrors(['type' => 'Ce contrat possède déjà un compteur de type "' . $request->type . '".'])
                ->withInput();
        }

        DB::table('compteurs')->insert([
            'contrat_num'   => $request->contrat_num,
            'matricule'     => $request->matricule,
            'type'          => $request->type,
            'dernier_index' => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('admin.compteurs.create')
            ->with('success', 'Compteur créé avec succès.');
    }

    // AJAX — GET /admin/compteurs/contrats-disponibles?secteur=Nord&type=eau
        public function contratsDisponibles(Request $request): JsonResponse
        {
            $contrats = DB::table('contrats')
                ->where('nom_secteur', $request->query('secteur'))
                ->whereRaw("
                    (
                        SELECT COUNT(DISTINCT type) FROM compteurs
                        WHERE compteurs.contrat_num = contrats.contrat_num
                    ) < 2
                ")
                ->orderBy('contrat_num')
                ->get(['contrat_num', 'adresse']);

            $contrats = $contrats->map(function ($c) {
                $c->existing_types = DB::table('compteurs')
                    ->where('contrat_num', $c->contrat_num)
                    ->pluck('type')
                    ->toArray();
                return $c;
            });

            return response()->json($contrats);
        }

    // AJAX — GET /admin/compteurs/generate-matricule
    public function generateMatricule(): JsonResponse
    {
        do {
            $matricule = 'CPT-' . strtoupper(bin2hex(random_bytes(4)));
        } while (DB::table('compteurs')->where('matricule', $matricule)->exists());

        return response()->json(['matricule' => $matricule]);
    }
}