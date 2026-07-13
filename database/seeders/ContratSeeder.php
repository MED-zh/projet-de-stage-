<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class ContratSeeder extends Seeder
{
    public function run(): void
    {
      \App\Models\Contrat::factory(10)->create();
    }
}