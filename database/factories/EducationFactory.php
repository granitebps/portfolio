<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startYear = $this->faker->year;

        return [
            'name' => $this->faker->name,
            'institute' => $this->faker->words(5, true),
            'start_year' => $startYear,
            'end_year' => $this->faker->year + 4,
        ];
    }
}
