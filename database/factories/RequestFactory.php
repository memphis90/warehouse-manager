<?php
// database/factories/RequestFactory.php

namespace Database\Factories;

use App\Models\{User, Item};
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'type' => 'existing_item',
            'item_name' => fake()->words(2, true),
            'category' => fake()->randomElement(['electronics', 'office_supplies']),
            'reason' => fake()->sentence(),
            'start_date' => fake()->dateTimeBetween('now', '+1 week'),
            'end_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'pending',
        ];
    }

    public function forPurchase(): static
    {
        return $this->state([
            'type' => 'purchase_request',
            'item_id' => null,
            'start_date' => null,
            'end_date' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state([
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state([
            'status' => 'rejected',
            'approved_by' => User::factory(),
            'approved_at' => now(),
            'admin_notes' => 'Not available for this period',
        ]);
    }
}