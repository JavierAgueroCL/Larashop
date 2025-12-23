<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence;
        return [
            'blog_category_id' => BlogCategory::inRandomOrder()->first()->id ?? BlogCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(3, true),
            'image_url' => 'https://placehold.co/800x600/eee/333?text=Blog+Image',
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_published' => true,
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}