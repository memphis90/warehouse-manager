<?php
// database/factories/ItemFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'MacBook Pro M3', 'Monitor Dell 27"', 'iPhone 15', 'iPad Pro',
                'Webcam Logitech', 'Mouse wireless', 'Tastiera meccanica',
                'Stampante HP', 'Scanner Epson', 'Proiettore Epson'
            ]),
            'category' => fake()->randomElement([
                'electronics', 'computers', 'peripherals', 'office_supplies', 'furniture'
            ]),
            'description' => fake()->sentence(10),
            'available' => true,
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(['available' => false]);
    }

    public function electronics(): static
    {
        return $this->state([
            'category' => 'electronics',
            'name' => fake()->randomElement(['iPhone', 'iPad', 'MacBook', 'Monitor'])
        ]);
    }
}