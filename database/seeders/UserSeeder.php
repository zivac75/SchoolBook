<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les utilisateurs.
     */
    public function run(): void
    {
        // Création de 20 utilisateurs avec des rôles variés
        User::factory(20)->create();
    }
}
