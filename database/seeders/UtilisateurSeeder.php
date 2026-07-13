<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
     public function run(): void
    {
        // ── 1. Admin ─────────────────────────────────────────
        User::factory()->admin()->create();

        // ── 2. One technicien per secteur ────────────────────
        $secteurs = DB::table('secteurs')->get();

            foreach ($secteurs as $secteur) {
                User::factory()->technicien($secteur->nom_secteur)->create(); // 👈 ->secteur_nom
            }
         foreach ($secteurs as $secteur) {
        User::factory(3)->caissier($secteur->nom_secteur)->create();
    }

        // ── 3. One client per contrat ────────────────────────
        $contrats = DB::table('contrats')->get();

        foreach ($contrats as $contrat) {
            User::factory()->client($contrat->contrat_num,$contrat->nom_secteur)->create();
        }
    }
}
