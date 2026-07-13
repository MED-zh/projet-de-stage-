<?php

namespace Database\Factories;

use App\Models\Contrat;
use App\Models\Secteur;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContratFactory extends Factory
{
    use HasFactory;
    protected $model = Contrat::class;

             public function definition(): array
    {
        // On définit une date de début aléatoire dans le passé (entre 1 an et 1 mois)
        $dateDebut = $this->faker->dateTimeBetween('-1 year', '-1 month');

        return [
            'contrat_num' => 'CON-' . strtoupper($this->faker->unique()->bothify('??####')),
            'adresse' => $this->faker->streetAddress(),
            'nom_secteur' => Secteur::inRandomOrder()->first()->nom_secteur,
            'ordre_tournee' => $this->faker->numberBetween(1, 100),
            
            // Date de début (on surcharge le useCurrent pour le factory pour avoir des dates variées)
            'date_debut' => $dateDebut,
            
            // Par défaut, le contrat est actif donc pas de date de fin
            'date_fin' => null,
            
            'status' => 'actif',
        ];
    }
}