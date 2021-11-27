<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'about' => $this->faker->paragraphs(
                asText: true
            ),
            'avatar' => $this->faker->imageUrl(),
            'phone' => '+62811112222',
            'address' => $this->faker->address(),
            'instagram' => $this->faker->url(),
            'facebook' => $this->faker->url(),
            'twitter' => $this->faker->url(),
            'linkedin' => $this->faker->url(),
            'github' => $this->faker->url(),
            'youtube' => $this->faker->url(),
            'cv' => $this->faker->imageUrl(),
            'nationality' => 'Indonesia',
            'languages' => ['Indonesia', 'English'],
            'freelance' => $this->faker->boolean(),
            'medium' => $this->faker->url(),
            'birth' => $this->faker->dateTime()
        ];
    }
}
