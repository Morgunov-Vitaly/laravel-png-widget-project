<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'is_published' => fake()->boolean(80),
            'rating' => fake()->numberBetween(1, 100),
        ];
    }

    public function published(): ReviewFactory|Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
            ];
        });
    }

    public function withRating100(): ReviewFactory|Factory
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 100,
        ]);
    }

    public function withRating5(): ReviewFactory|Factory
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
        ]);
    }
}
