<?php

namespace App\Http\Controllers\Users; 

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Contrat;
use App\Models\Facture;
use App\Models\Releve;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ================= REGISTER =================
                    public function showAuth()
            {
                return view('auth.auth');
            }
                public function register(RegisterRequest $request)
        {
          
              $contrat = Contrat::where('contrat_num', $request->contrat_num)->first();

                    if (!$contrat) {
                        return back()
                            ->withErrors(['contrat_num' => 'Numéro de contrat invalide'])
                            ->withInput();
                    }   // 2. Check if a User is already using this specific contract (Uniqueness check)
                        $alreadyRegistered = User::where('contrat_num', $request->contrat_num)->exists();

                        if ($alreadyRegistered) {
                            return back()
                                ->withErrors(['contrat_num' => 'Ce numéro de contrat est déjà utilisé par un autre compte.'])
                                ->withInput();
                        }
                            
                                        
            $user = User::create([
                'name' =>  $request->name,
                'email' => $request->email,
                'cin' => $request->cin,
                'password' => $request->password, // auto hashed
                'role' => 'client', // 🔥 FORCE CLIENT ROLE
                'phone' => $request->phone,
                'adresse' => $request->adresse, 
                'contrat_num' => $request->contrat_num,
            ]);

            // auto login after register
            Auth::login($user);

            return redirect()->route('user');
        }

    // ================= LOGIN =================
   public function login(LoginRequest $request)
                {
                    if (!Auth::attempt([
                        'email'    => $request->gmail,   // ← your renamed field
                        'password' => $request->pass,    // ← your renamed field
                    ])) {
                        return back()
                            ->withErrors(['gmail' => 'Email ou mot de passe incorrect.'], 'login')
                            ->withInput();
                    }
                    
                    $request->session()->regenerate();
                     
                    return $this->redirectByRole(Auth::user());
                }
    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/authentification');
    }

    // ================= ROLE REDIRECTION =================
    private function redirectByRole($user)
    {   
         
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isTechnicien()) {
            return redirect()->route('tech.index');
        }
        if ($user->isCaissier()) {
             return redirect()->route('caissier.dashboard');
       }

       return redirect()->route('user');
    } 


    // ================= DASHBOARD =================


public function dashboard(Request $request)
{
    // 🔐 Ensure user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // ⚠️ Safety: user must have contrat
    if (!$user->contrat_num) {
        abort(403, 'Aucun contrat associé à cet utilisateur');
    }

    $selectedYear = $request->get('year', now()->year);
    $selectedType = $request->get('type', 'eau');

    // 📊 Get releves
    $releves = Releve::where('contrat_num', $user->contrat_num)->get();

    // 📅 Extract years
    $years = $releves
        ->map(fn($r) => Carbon::parse($r->date_releve)->year)
        ->unique()
        ->sort()
        ->values(); 

// Fallback if no releves
   $selectedYear = $request->get('year', $years->first() ?? now()->year);

    // 📆 Filter by year
    $yearReleves = $releves->filter(
        fn($r) => Carbon::parse($r->date_releve)->year == $selectedYear
    );

    // 📈 Prepare monthly arrays
    $eauByMonth  = array_fill(0, 12, null);
    $elecByMonth = array_fill(0, 12, null);

    foreach ($yearReleves as $r) {
        $month = Carbon::parse($r->date_releve)->month - 1;

        if ($r->type === 'eau') {
            $eauByMonth[$month] = $r->consommation;
        } else {
            $elecByMonth[$month] = $r->consommation;
        }
    }

    // 📊 Clean values
    $eauValues  = array_filter($eauByMonth, fn($v) => $v !== null);
    $elecValues = array_filter($elecByMonth, fn($v) => $v !== null);

    // 📅 Last record (optimized)
    $last = $yearReleves->sortByDesc('date_releve')->first();

    // 📊 Correct month count logic
    $monthCount = $selectedType === 'eau'
        ? count($eauValues)
        : ($selectedType === 'elec'
            ? count($elecValues)
            : max(count($eauValues), count($elecValues)));
            $factures = Facture::where('contrat_num', $user->contrat_num)
    ->where('annee', $selectedYear)
    ->get();
     $selectedMonth = $request->get('month', null); // null = pas de mois sélectionné

// Mois disponibles pour l'année sélectionnée
$availableMonths = $factures->map(fn($f) => $f->mois)->unique()->sort()->values();

// Si mois sélectionné, récupère la facture + relevé de ce mois
$monthDetail = null;
if ($selectedMonth) {
    $eauFacture  = $factures->where('type', 'eau')->where('mois', $selectedMonth)->first();
    $elecFacture = $factures->where('type', 'electricite')->where('mois', $selectedMonth)->first();

    $eauReleve  = $yearReleves->where('type', 'eau')
        ->filter(fn($r) => Carbon::parse($r->date_releve)->month == $selectedMonth)->first();
    $elecReleve = $yearReleves->where('type', 'electricite')
        ->filter(fn($r) => Carbon::parse($r->date_releve)->month == $selectedMonth)->first();

    $monthDetail = compact('eauFacture', 'elecFacture', 'eauReleve', 'elecReleve');
}       

    return view('user.dashboard', [
        'releves'      => $releves,
        'years'        => $years,
        'selectedYear' => $selectedYear,
        'selectedType' => $selectedType,
        'eauByMonth'   => array_values($eauByMonth),
        'elecByMonth'  => array_values($elecByMonth),
         'selectedMonth'   => $selectedMonth,
        'availableMonths' => $availableMonths,
        'monthDetail'     => $monthDetail,
        'totalEau'     => array_sum($eauValues),
        'totalElec'    => array_sum($elecValues),
        'avgEau'       => count($eauValues)
                            ? round(array_sum($eauValues) / count($eauValues))
                            : 0,
        'avgElec'      => count($elecValues)
                            ? round(array_sum($elecValues) / count($elecValues))
                            : 0,
        'monthCount'   => $monthCount,
        'lastMonth'    => $last
                            ? Carbon::parse($last->date_releve)->translatedFormat('F Y')
                            : '—',
    ]);
}

                // ================= PROFIL =================
                     public function profil()
                        {
                            $user = User::with('contrat')->find(Auth::id());
                            
                            return view('user.profil', compact('user'));
                        }

                // ================= CONTRAT =================
                public function contrat()
{
    $user = User::with('contrat')->find(Auth::id());
    $contrat = $user->contrat;

    return view('user.contrat', compact('user', 'contrat'));
}
}
