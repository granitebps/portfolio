<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->paragraph();
        return [
            'user_id' => User::factory()->create(),
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => $this->faker->paragraph(10),
            'image' => $this->faker->filePath(),
        ];
    }
}
