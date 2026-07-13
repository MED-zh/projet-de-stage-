<?php

namespace Database\Factories;

use App\Models\Releve;
use App\Models\Contrat;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReleveFactory extends Factory
{
    protected $model = Releve::class;

    public function definition(): array
    {
        $indexDepart = $this->faker->numberBetween(100, 500);
        $indexFin = $indexDepart + $this->faker->numberBetween(10, 100);

      return [
            'date_releve' => $this->faker->date(),
            'type' => $this->faker->randomElement(['eau', 'electricite']),
            'index_depart' => $indexDepart,
            'index_fin' => $indexFin,
            'consommation' => $indexFin - $indexDepart,
            
            // Logic to pick an existing contract number
            'contrat_num' => function () {
                return Contrat::inRandomOrder()->first()->contrat_num 
                       ?? Contrat::factory()->create()->contrat_num;
            },

            // Logic to pick an existing technician CIN
            'tech_cin' => function () {
                return User::where('role', 'technicien')->inRandomOrder()->first()->cin 
                       ?? User::factory()->create(['role' => 'technicien'])->cin;
            },
        ];
    }
}