<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de 5 services réalistes
        $services = [
            ['Consultation', 'Consultation individuelle avec un expert.'],
            ['Support technique', 'Assistance technique pour les utilisateurs.'],
            ['Formation', 'Session de formation sur un sujet spécifique.'],
            ['Atelier', 'Atelier pratique en groupe.'],
            ['Coaching', 'Coaching personnalisé pour le développement professionnel.'],
        ];
        foreach ($services as $s) {
            Service::factory()->create([
                'name' => $s[0],
                'description' => $s[1],
            ]);
        }
    }
}
