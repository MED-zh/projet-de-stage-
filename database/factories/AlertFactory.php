<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\User;
use App\Models\Contrat;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->paragraph(),
            'date_alert' => $this->faker->date(),
            'status' => $this->faker->randomElement(['en_attente', 'en_cours', 'resolu', 'annule']),
            
            // This picks a random existing Tech CIN from the users table
            'tech_cin' => User::pluck('cin')->random(),
            
            // This picks a random existing Contract Number
            'contrat_num' => Contrat::pluck('contrat_num')->random(),
        ];
    }
}