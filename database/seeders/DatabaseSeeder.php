<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AlertSeeder;
use Database\Seeders\CompteurSeeder;
use Database\Seeders\ContratSeeder;
use Database\Seeders\SecteurSeeder;

use Database\Seeders\UtilisateurSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
     public function run(): void
        {
            $this->call([
                 
                // SecteurSeeder::class,
                // ContratSeeder::class,
                // CompteurSeeder::class,
                // UtilisateurSeeder::class,
                // PrixSeeder::class,
                ReleveSeeder::class,
                AlertSeeder::class,
                FactureSeeder::class,
            ]);
               
           
        }
}
