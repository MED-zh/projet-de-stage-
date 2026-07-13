<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\User;
use Dotenv\Util\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaissierController extends Controller
{
    // ── Dashboard ────────────────────────────────────────────────
public function dashboard()
{
    $aujourdhui = now()->toDateString();
    $mois       = now()->month;
    $annee      = now()->year;

    // KPIs
    $encaisseAujourdhui = Paiement::whereDate('date_paiement', $aujourdhui)
        ->where('recu_par', Auth::id())
        ->sum('montant_total');

    $encaisseMois = Paiement::whereMonth('date_paiement', $mois)
        ->whereYear('date_paiement', $annee)
        ->where('recu_par', Auth::id())
        ->sum('montant_total');

    $impayees  = Facture::where('status', 'impayee')->count();
    $enRetard  = Facture::where('status', 'en_retard')->count();

    // Last 5 payments today
    $derniersPaiements = Paiement::with(['facture', 'contrat.utilisateur'])
        ->where('recu_par', Auth::id())
        ->whereDate('date_paiement', $aujourdhui)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    // Répartition eau / elec today
    $repartition = Paiement::with('facture')
        ->where('recu_par', Auth::id())
        ->whereDate('date_paiement', $aujourdhui)
        ->get()
        ->groupBy(fn($p) => $p->facture?->type)
        ->map(fn($group) => [
            'count'  => $group->count(),
            'total'  => $group->sum('montant_total'),
        ]);

    // Urgent invoices (top 5 only)
    $facturesUrgentes = Facture::with(['contrat.utilisateur', 'contrat'])
        ->whereIn('status', ['impayee', 'en_retard'])
        ->orderBy('date_echeance')
        ->limit(5)
        ->get();

    return view('caissier.dashboard', compact(
        'encaisseAujourdhui', 'encaisseMois',
        'impayees', 'enRetard',
        'derniersPaiements', 'repartition',
        'facturesUrgentes'
    ));
}
    // ── Liste toutes les factures ─────────────────────────────────
public function factures(Request $request)
{
    $query = Facture::with('contrat.utilisateur')
        ->whereIn('status', ['impayee', 'en_retard']);

    if ($request->filled('facture_id')) {
        $query->where('numero_facture', 'like', '%' . $request->facture_id . '%');
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('facture_id') || $request->filled('type')) {
        $query->orderByRaw("CASE WHEN numero_facture = ? THEN 0 ELSE 1 END", [$request->facture_id ?? ''])
              ->orderBy('date_echeance', 'asc');
    } else {
        $query->inRandomOrder();
    }

    // Count separately to avoid inRandomOrder() breaking paginate total
    $total = (clone $query)->toBase()->getCountForPagination();
 

    $factures = $query->paginate(15)->withQueryString();
     

    return view('caissier.factures', compact('factures', 'total'));
}

    // ── Liste tous les paiements ──────────────────────────────────
  public function index(Request $request)
{
    $query = Paiement::with([
            'facture',
            'caissier',
            'contrat',
            'contrat.utilisateur',
        ])
        ->orderByDesc('date_paiement');

    if ($request->filled('search')) {
        $query->whereHas('facture', fn($q) =>
            $q->where('numero_facture', 'like', '%' . $request->search . '%')
        );
    }

    $paiements   = $query->paginate(15)->withQueryString();
    $totalFiltre = $query->sum('montant_total');

    return view('caissier.index', compact(
        'paiements', 'totalFiltre'
    ));
}

    // ── Formulaire nouveau paiement ───────────────────────────────
  
    // ── AJAX — factures impayées par contrat + année + type ───────
    public function facturesImpayees(Request $request): JsonResponse
    {
        $request->validate([
            'contrat_num' => 'required|exists:contrats,contrat_num',
            'annee'       => 'required|integer',
            'type'        => 'required|in:eau,electricite',
        ]);

        $factures = Facture::where('contrat_num', $request->query('contrat_num'))
                        ->where('annee',       $request->query('annee'))
                        ->where('type',        $request->query('type'))
                        ->whereIn('status', ['impayee', 'en_retard'])
                        ->orderBy('mois')
                        ->get(['id', 'numero_facture', 'mois', 'annee', 'type', 'montant_ttc', 'status']);

        return response()->json($factures);
    }
 public function payer(Facture $facture)
{
    if (!in_array($facture->status, ['impayee', 'en_retard'])) {
        return redirect()->route('caissier.factures.index')
            ->with('error', 'Cette facture est déjà payée.');
    }
$contrat = DB::table('contrats')
    ->where('contrat_num', $facture->contrat_num)
    ->first();
     

// nom_secteur IS the secteur name directly — no join needed
$secteur = $contrat?->NOM_SECTEUR ?? null;

$client = DB::table('users')
    ->where('contrat_num', $facture->contrat_num)
    ->first();

$numeroRecu = 'PAI-' . strtoupper(base_convert(time(), 10, 36))
                    . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));

return view('caissier.payer', compact('facture', 'contrat', 'secteur', 'client', 'numeroRecu'));
}
    // ── Enregistrer le paiement ───────────────────────────────────
   public function store(Request $request)
{
    $request->validate([
        'contrat_num'   => 'required|exists:contrats,contrat_num',
        'date_paiement' => 'required|date',
        'notes'         => 'nullable|string|max:500',
        'numero_recu'   => 'required|string|unique:paiements,numero_recu|max:60',
        'facture_ids'   => 'required|array|min:1',
        'facture_ids.*' => 'required|exists:factures,id',
    ]);

    $factures = Facture::whereIn('id', $request->facture_ids)->get();

    foreach ($factures as $facture) {
        if ($facture->contrat_num !== $request->contrat_num) {
            return back()->withErrors(['facture_ids' => 'Facture invalide.'])->withInput();
        }
        if (!in_array($facture->status, ['impayee', 'en_retard'])) {
            return back()->withErrors(['facture_ids' => "{$facture->numero_facture} est déjà payée."])->withInput();
        }
    }

    DB::transaction(function () use ($request, $factures) {
        foreach ($factures as $facture) {
            Paiement::create([
                'numero_recu'   => $request->numero_recu,   // from form
                'facture_id'    => $facture->id,
                'recu_par'      => Auth::id(),
                'contrat_num'   => $facture->contrat_num,
                'montant_total' => $facture->montant_ttc,   // always from DB
                'date_paiement' => $request->date_paiement,
                'notes'         => $request->notes,
            ]);

            $facture->update(['status' => 'payee']);
        }
    });

 return redirect()->route('caissier.factures.index')
    ->with('success', 'Paiement enregistré avec succès.')
    ->with('download_recu', $request->numero_recu);
}

    // ── Page reçu ─────────────────────────────────────────────────


  // ── PDF reçu ──────────────────────────────────────────────────
public function recu(string $numero)
{
    $paiements = Paiement::with([
            'facture',
            'caissier',
            'contrat',
            'contrat.utilisateur',
        ])
        ->where('numero_recu', $numero)
        ->get();

    abort_if($paiements->isEmpty(), 404);

    $premier      = $paiements->first();
    $montantTotal = $paiements->sum('montant_total');
    $caissier     = $premier->caissier;
    $contrat      = $premier->contrat;
    $client       = $contrat?->utilisateur;

    return view('caissier.recu', compact(
        'paiements', 'caissier', 'client', 'contrat', 'montantTotal', 'numero'
    ));
}
   public function recuPdf(string $numero)
{
    $paiements = Paiement::with(['facture', 'caissier', 'contrat.utilisateur'])
        ->where('numero_recu', $numero)
        ->get();

    abort_if($paiements->isEmpty(), 404);

    $premier      = $paiements->first();        
    $montantTotal = $paiements->sum('montant_total');
    $caissier     = $premier->caissier;
    $contrat      = $premier->contrat;          // ← new
    $client       = $contrat?->utilisateur;     // ← fixed (was ->contrat->user)

    return \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'caissier.recu_pdf',
        compact('paiements', 'caissier', 'client', 'contrat', 'montantTotal', 'numero')
    )
    ->setPaper('a5', 'portrait')
    ->download('Recu_' . $numero . '.pdf');
}
    // ── Générer numéro reçu ───────────────────────────────────────
    public function checkRecu(Request $request)
{
    $exists = Paiement::where('numero_recu', $request->query('numero'))->exists();
    return response()->json(['exists' => $exists]);
}
  
}