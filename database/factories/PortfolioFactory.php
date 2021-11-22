<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'desc' => $this->faker->paragraphs(5),
            'thumbnail' => $this->faker->filePath(),
            'type' => $this->faker->numberBetween(1, 2),
            'url' => $this->faker->url,
        ];
    }
}
