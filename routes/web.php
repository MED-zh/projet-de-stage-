<?php

use App\Http\Controllers\AlerteController;
use App\Http\Controllers\CaissierController;
use App\Http\Controllers\CompteurController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ReleveController;
use App\Http\Controllers\users\AdminController;
use App\Http\Controllers\users\TechnicienController;
use App\Http\Controllers\users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage');
})->name('landing');

Route::get('/authentification', [UserController::class, 'showAuth'])->name('login');
Route::get('/facture', [FactureController::class, 'index'])->name('factures.index');
Route::get('/facture/telecharger/{id}', [FactureController::class, 'telecharger'])->name('factures.telecharger');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/authentification', [UserController::class, 'login'])->name('authentification');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// ── User ────────────────────────────────────────────────────────
Route::middleware(['auth', 'hasContract', 'prevent.back'])->group(function () {
    Route::get('/user', [UserController::class, 'dashboard'])->name('user');
    Route::get('/user/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::get('/user/contrat', [UserController::class, 'contrat'])->name('user.contrat');
});

// ── Admin ───────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'prevent.back'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/contrats',           [ContratController::class, 'contratsIndex'])->name('contrats.index');
    Route::get('/contrats/create',    [ContratController::class, 'contratsCreate'])->name('contrats.create');
    Route::post('/contrats',          [ContratController::class, 'contratsStore'])->name('contrats.store');
    Route::get('/contrats/{id}/edit', [ContratController::class, 'contratsEdit'])->name('contrats.edit');
    Route::put('/contrats/{id}',      [ContratController::class, 'contratsUpdate'])->name('contrats.update');
    Route::delete('/contrats/{id}',   [ContratController::class, 'contratsDestroy'])->name('contrats.destroy');

    Route::get('/techniciens',             [TechnicienController::class, 'techniciensIndex'])->name('techniciens.index');
    Route::get('/techniciens/create',      [TechnicienController::class, 'techniciensCreate'])->name('techniciens.create');
    Route::post('/techniciens',            [TechnicienController::class, 'techniciensStore'])->name('techniciens.store');
    Route::get('/techniciens/{id}/edit',   [TechnicienController::class, 'techniciensEdit'])->name('techniciens.edit');
    Route::put('/techniciens/{id}',        [TechnicienController::class, 'techniciensUpdate'])->name('techniciens.update');
    Route::delete('/techniciens/{id}',     [TechnicienController::class, 'techniciensDestroy'])->name('techniciens.destroy');

    Route::get('/alerts',              [AlerteController::class, 'alertsIndex'])->name('alerts.index');
    Route::get('/alerts/{alert}/edit', [AlerteController::class, 'alertsEdit'])->name('alerts.edit');
    Route::put('/alerts/{alert}',      [AlerteController::class, 'alertsUpdate'])->name('alerts.update');

    Route::get('compteurs/contrats-disponibles', [CompteurController::class, 'contratsDisponibles'])->name('compteurs.contrats-disponibles');
    Route::get('compteurs/generate-matricule',   [CompteurController::class, 'generateMatricule'])->name('compteurs.generate-matricule');
    Route::resource('compteurs', CompteurController::class)->names('compteurs');

    Route::get('/consommation', [AdminController::class, 'consommation'])->name('consommation');
});

// ── Technicien ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:technicien', 'prevent.back'])->group(function () {
    Route::get('/technicien',            [TechnicienController::class, 'affiche'])->name('tech.index');
    Route::post('/technicien/recherche', [TechnicienController::class, 'recherche'])->name('tech.recherche');
    Route::post('/technicien/releve',    [ReleveController::class, 'storeReleve'])->name('tech.releve.store');
    Route::post('/technicien/alerte',    [AlerteController::class, 'storeAlerte'])->name('tech.alerte.store');
    Route::get('/technicien/alertes',    [AlerteController::class, 'alerts'])->name('tech.alertes');
});

Route::middleware(['auth', 'role:caissier', 'prevent.back'])->prefix('caissier')->name('caissier.')->group(function () {
    Route::get('/dashboard',                        [CaissierController::class, 'dashboard'])->name('dashboard');
    Route::get('/factures',                         [CaissierController::class, 'factures'])->name('factures.index');
    Route::get('/paiements',                        [CaissierController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/create',                 [CaissierController::class, 'create'])->name('paiements.create');
    Route::post('/factures/{facture}/payer', [CaissierController::class, 'store'])->name('factures.store');
    Route::get('/paiements/factures-impayees',      [CaissierController::class, 'facturesImpayees'])->name('paiements.factures-impayees');
    Route::get('/paiements/recu/{numero}',          [CaissierController::class, 'recu'])->name('paiements.recu');
    Route::get('/paiements/recu/{numero}/pdf',      [CaissierController::class, 'recuPdf'])->name('paiements.recu-pdf');
    Route::get('/factures/{facture}/payer', [CaissierController::class, 'payer'])->name('factures.payer');
    Route::get('/check-recu', [CaissierController::class, 'checkRecu'])->name('check-recu');
});
// ── Password Reset ──────────────────────────────────────────────
Route::get('/forgot-password',        fn() => view('auth.forgot_password'))->name('password.request');
Route::post('/forgot-password',       [PasswordResetController::class, 'sendLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password',        [PasswordResetController::class, 'reset'])->name('password.update');