<?php

namespace Database\Factories\Book;

use App\Models\Book\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genre = Genre::query()->inRandomOrder()->first();

        return [
            'title' => fake()->words(3, true),
            'author' => fake()->name(),
            'published_date' => fake()->date(),
            'genre_id' => $genre->id,
        ];
    }
}
