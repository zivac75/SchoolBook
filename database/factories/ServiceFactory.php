<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            ['Consultation', 'Consultation individuelle avec un expert.'],
            ['Support technique', 'Assistance technique pour les utilisateurs.'],
            ['Formation', 'Session de formation sur un sujet spécifique.'],
            ['Atelier', 'Atelier pratique en groupe.'],
            ['Coaching', 'Coaching personnalisé pour le développement professionnel.'],
        ];
        $service = fake()->randomElement($services);
        return [
            'name' => $service[0],
            'description' => $service[1],
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
            'is_active' => fake()->boolean(90),
        ];
    }
}
