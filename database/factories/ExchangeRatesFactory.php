<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExchangeRates>
 */
class ExchangeRatesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'based_currency' => $this->faker->word,
            'target_currency' => $this->faker->word,
            'rate' => $this->faker->numberBetween(1,100),
        ];
    }
}
