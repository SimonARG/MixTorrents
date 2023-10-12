<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Upload>
 */
class UploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'magnet' => 'aaaaaaaaaaaaaaaaaaaaaaaaaa',
            'path' => '../files/torrent.torrent',
            'info' => fake()->sentence(4),
            'description' => fake()->text(2000),
            'size' => fake()->numberBetween(1, 200) . 'GB',
            'seeders' => fake()->numberBetween(1, 200),
            'leechers' => fake()->numberBetween(1, 200),
            'downloads' => fake()->numberBetween(1, 200),
            'hash' => fake()->text(16)
        ];
    }
}
