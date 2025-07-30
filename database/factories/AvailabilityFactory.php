<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 days', '+1 month');
        $end = (clone $start)->modify('+' . fake()->randomElement([30, 45, 60, 90]) . ' minutes');
        return [
            'service_id' => \App\Models\Service::factory(),
            'start_datetime' => $start,
            'end_datetime' => $end,
        ];
    }
}
