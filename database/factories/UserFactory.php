<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Contrat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'phone'             => $this->faker->numerify('06########'),
            'adresse'           => $this->faker->address(),
            'cin'               => strtoupper($this->faker->bothify('??######')),
            'role'              => 'client',
            'nom_secteur'       => null,
            'contrat_num'       => null,
            'remember_token'    => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name'        => 'Admin ONEE',
            'email'       => 'admin@onee.ma',
            'cin'         => 'ADMIN001',
            'role'        => 'admin',
            'nom_secteur' => null,
            'contrat_num' => null,
        ]);
    }

        public function technicien(string $nomSecteur): static
        {
            return $this->state(fn () => [
                'role'        => 'technicien',
                'nom_secteur' => $nomSecteur,
                'contrat_num' => null,
            ]);
        }
            public function client(string $contratNum, string $nomSecteur): static
            {
                return $this->state(fn () => [
                    'role'        => 'client',
                    'nom_secteur' => $nomSecteur,
                    'contrat_num' => $contratNum,
                ]);
            }
            public function caissier(string $nomSecteur): static
                {
                    return $this->state(fn () => [
                        'role'        => 'caissier',
                        'nom_secteur' => $nomSecteur,
                        'contrat_num' => null,
                    ]);
                }


    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}