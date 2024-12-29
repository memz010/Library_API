<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sell>
 */
class SellFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => fake()->numberBetween(1,500),
            'user_id' => fake()->numberBetween(1,100),
            'quantity' => fake()->numberBetween(1,5),
            'price' => fake()->numberBetween(50,80)
        ];
    }
}
