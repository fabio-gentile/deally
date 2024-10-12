<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentDiscussion>
 */
class CommentDiscussionFactory extends Factory
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
            'discussion_id' => \App\Models\Discussion::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
