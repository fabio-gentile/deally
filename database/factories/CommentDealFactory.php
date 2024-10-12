<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentDeal>
 */
class CommentDealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(rand(5, 20)),
            'deal_id' => \App\Models\Deal::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
