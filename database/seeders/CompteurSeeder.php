<?php

namespace Database\Seeders;

use App\Models\Compteur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contrats = DB::table('contrats')->get();

        foreach ($contrats as $contrat) {

            // 1 compteur eau per contrat
            Compteur::factory()->eau()->create([
                'contrat_num' => $contrat->contrat_num,
            ]);

            // 1 compteur electricite per contrat
            Compteur::factory()->electricite()->create([
                'contrat_num' => $contrat->contrat_num,
            ]);
        }
    }
}
