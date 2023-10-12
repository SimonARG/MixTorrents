<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->userName(),
            'pass' => fake()->password(),
            'email' => fake()->email(),
            'pic' => '..\pictures\'.jpeg',
            'remember_token' => null,
            'about' => fake()->text(400)
        ];
    }
}