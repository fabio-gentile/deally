<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = rand(0, 5) === 0 ? null : rand(0, 500);
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph(rand(3, 10)),
            'deal_url' => $this->faker->url,
            'votes' => rand(-10, 100),
            'start_date' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'expiration_date' => $this->faker->dateTimeBetween('-3 days', '+14 days'),
            'user_id' => \App\Models\User::all()->random()->id,
            'category_deal_id' => \App\Models\CategoryDeal::all()->random()->id,
            'price' => $price,
            'original_price' => $price ? $price + rand(50, 150) : null,
            'promo_code' => rand(0, 5) === 0 ? null : $this->faker->word,
        ];
    }
}
