<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $publishedAt = now()->subHours(random_int(0, 24 * 365));
        return [
            'title' => $this->faker->sentence(),
            'summary' => $this->faker->paragraph(),
            'article_url' => $this->faker->url() . "/" . $this->faker->uuid(),
            'image_url' => $this->faker->imageUrl(),
            'published_at' => $publishedAt,
            'source_id' => fn() => Source::factory(),
            'category_id' => fn() => Category::factory(),
            'author_id' => fn() => Author::factory(),
            'created_at' => $publishedAt->addDay(),
            'updated_at' => $publishedAt->addDay(),
        ];
    }
}
