<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReleveSeeder extends Seeder
{
    // public function run(): void
    // {
    //     // Get 3 contrat numbers
    //     $contratNums = DB::table('contrats')->pluck('contrat_num')->take(3);
         
  
    //     // Get all compteurs (eau + electricite) for those 3 contrats
    //     $compteurs = DB::table('compteurs')
    //         ->whereIn('contrat_num', $contratNums)
    //         ->select('contrat_num', 'matricule', 'type')
    //         ->get();

    //     // Get one technicien CIN
    //     $technicien = DB::table('users')
    //         ->where('role', 'technicien')
    //         ->value('cin');
       
    //     $start = Carbon::create(2024, 1, 15);
    //     $end   = Carbon::now()->startOfMonth()->addDays(14);

    //     $indexes = [];

    //     foreach ($compteurs as $compteur) {
    //         $key = $compteur->matricule;
    //         $indexes[$key] = 0;

    //         $current = $start->copy();

    //         while ($current->lte($end)) {
    //             $conso = $compteur->type === 'eau' ? rand(10, 20) : rand(80, 150);

    //             DB::table('releves')->insert([
    //                 'date_releve'  => $current->toDateString(),
    //                 'type'         => $compteur->type,
    //                 'index_depart' => $indexes[$key],
    //                 'index_fin'    => $indexes[$key] + $conso,
    //                 'consommation' => $conso,
    //                 'matricule'    => $compteur->matricule,
    //                 'contrat_num'  => $compteur->contrat_num,
    //                 'tech_cin'     => $technicien,
    //                 'created_at'   => now(),
    //                 'updated_at'   => now(),
    //             ]);

    //             $indexes[$key] += $conso;
    //             $current->addMonth();
    //         }
    //     }
    // }
      public function run(): void
    {
        $contratNum = 'CON-VM5925';

        $compteurs = DB::table('compteurs')
            ->where('contrat_num', $contratNum)
            ->select('contrat_num', 'matricule', 'type')
            ->get();

        $technicien = DB::table('users')
            ->where('role', 'technicien')
            ->value('cin');

        $start = Carbon::create(2024, 1, 15);
        $end   = Carbon::now()->startOfMonth()->addDays(14);

        $indexes = [];

        foreach ($compteurs as $compteur) {
            $key = $compteur->matricule;
            $indexes[$key] = 0;
            $current = $start->copy();

            while ($current->lte($end)) {
                $conso = $compteur->type === 'eau' ? rand(10, 20) : rand(80, 150);

                DB::table('releves')->insert([
                    'date_releve'  => $current->toDateString(),
                    'type'         => $compteur->type,
                    'index_depart' => $indexes[$key],
                    'index_fin'    => $indexes[$key] + $conso,
                    'consommation' => $conso,
                    'matricule'    => $compteur->matricule,
                    'contrat_num'  => $compteur->contrat_num,
                    'tech_cin'     => $technicien,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $indexes[$key] += $conso;
                $current->addMonth();
            }
        }
    }
}