<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\Article;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */

    protected $model = Article::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'source_id' => $this->faker->numberBetween(1, 3),
            'external_link' => $this->faker->url,
            'published_date' => $this->faker->dateTimeBetween('-1 years', 'now'), 
            'data' => json_encode([
                'author' => $this->faker->name,
                'summary' => $this->faker->paragraph,
                'tags' => $this->faker->words(5),
            ]),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
