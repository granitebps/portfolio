<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startDate = $this->faker->date;
        $currentJob = $this->faker->boolean;

        return [
            'company' => $this->faker->company,
            'position' => $this->faker->jobTitle,
            'start_date' => $startDate,
            'end_date' => $currentJob ? null : $this->faker->date,
            'current_job' => $currentJob,
            'desc' => $this->faker->words(10, true)
        ];
    }
}
