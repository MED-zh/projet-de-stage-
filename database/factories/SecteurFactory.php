<?php

namespace Database\Factories;

use App\Models\Secteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Secteur>
 */
class SecteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Liste des vrais quartiers de Safi
        $quartiersSafi = [
            'Plateau', 
            'Biada', 
            'Kourchi', 
            'Haniat El Hamra', 
            'Sania', 
            'Mghogha', 
            'Zouhour', 
            'Océan', 
            'Jrifat', 
            'Azib Derai'
        ];

        // On utilise l'index unique du faker pour ne pas avoir de doublons
        $nomQuartier = $this->faker->unique()->randomElement($quartiersSafi);

        return [
            // Numéro de secteur unique (ex: SEC-1, SEC-2...)
            'number_secteur' => 'SEC-' . $this->faker->unique()->numberBetween(1, 100),
            
            // Nom du secteur basé sur Safi
            'nom_secteur' => $nomQuartier,
            
            'ville' => 'Safi',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
