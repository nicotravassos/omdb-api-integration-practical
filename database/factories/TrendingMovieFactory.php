<?php

namespace Database\Factories;

use App\Models\TrendingMovie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrendingMovie>
 */
class TrendingMovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imdb_id' => $this->faker->unique()->regexify('tt[0-9]{7}'),
            'title' => $this->faker->sentence(3),
            'search_count' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
