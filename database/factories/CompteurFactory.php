<?php

namespace Database\Factories;

use App\Models\Compteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Compteur>
 */
class CompteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
    {
        return [
            'matricule'     => strtoupper($this->faker->bothify('CPT-??####')),
            'dernier_index' => 0,
            // type + contrat_num injected from seeder
        ];
    }

    public function eau(): static
    {
        return $this->state(fn () => ['type' => 'eau']);
    }

    public function electricite(): static
    {
        return $this->state(fn () => ['type' => 'electricite']);
    }
}
