<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResetPasswordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'token' => $this->faker->bothify('####????'),
            'is_valid' => $this->faker->boolean(),
            'expired_at' => $this->faker->dateTime()
        ];
    }
}
