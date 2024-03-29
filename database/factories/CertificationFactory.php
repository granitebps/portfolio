<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CertificationFactory extends Factory
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
            'institution' => $this->faker->words(5, true),
            'link' => $this->faker->url,
            'published' => $this->faker->date
        ];
    }
}
