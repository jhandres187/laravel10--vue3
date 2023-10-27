<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->name;
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->text(120),
            'content' => fake()->text(120),
            'posted' => $this->postedRandom(),
            'category_id' => rand(1,4)
        ];
    }

    public function postedRandom(){
        return rand(0, 1) == 1?'yes':'not';
    }
}
