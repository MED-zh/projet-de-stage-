<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // ── EAU (ONEE Morocco) ────────────────────────────────
        $tarifsEau = [
            [
                'tranche'       => 1,
                'limite_min'    => 0,
                'limite_max'    => 6,
                'prix_unitaire' => 1.69,
                'label'         => 'Tranche sociale',
            ],
            [
                'tranche'       => 2,
                'limite_min'    => 6,
                'limite_max'    => 20,
                'prix_unitaire' => 4.38,
                'label'         => 'Tranche normale basse',
            ],
            [
                'tranche'       => 3,
                'limite_min'    => 20,
                'limite_max'    => 40,
                'prix_unitaire' => 5.57,
                'label'         => 'Tranche normale haute',
            ],
            [
                'tranche'       => 4,
                'limite_min'    => 40,
                'limite_max'    => 9999,
                'prix_unitaire' => 7.06,
                'label'         => 'Tranche élevée',
            ],
        ];

        // ── ELECTRICITE (ONEE Morocco) ────────────────────────
        $tarifsElec = [
            [
                'tranche'       => 1,
                'limite_min'    => 0,
                'limite_max'    => 100,
                'prix_unitaire' => 0.8970,
                'label'         => 'Tranche sociale',
            ],
            [
                'tranche'       => 2,
                'limite_min'    => 100,
                'limite_max'    => 200,
                'prix_unitaire' => 1.0580,
                'label'         => 'Tranche normale basse',
            ],
            [
                'tranche'       => 3,
                'limite_min'    => 200,
                'limite_max'    => 500,
                'prix_unitaire' => 1.1760,
                'label'         => 'Tranche normale haute',
            ],
            [
                'tranche'       => 4,
                'limite_min'    => 500,
                'limite_max'    => 9999,
                'prix_unitaire' => 1.3940,
                'label'         => 'Tranche élevée',
            ],
        ];

        foreach ($tarifsEau as $t) {
            DB::table('prix')->insert([
                ...$t,
                'type'       => 'eau',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($tarifsElec as $t) {
            DB::table('prix')->insert([
                ...$t,
                'type'       => 'electricite',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
