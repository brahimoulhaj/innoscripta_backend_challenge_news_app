<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;

test('can get prefered articles', function () {
    $user = User::factory()->create();
    $user->preferences()->create([
        'preferred_sources' => [1, 2],
        'preferred_categories' => [1, 2],
        'preferred_authors' => ['John Doe', 'Jane Doe'],
    ]);

    Category::factory()->create(['name' => 'category 1']);
    Category::factory()->create(['name' => 'category 2']);
    Category::factory()->create(['name' => 'category 3']);

    Source::factory()->create(['name' => 'source 1']);
    Source::factory()->create(['name' => 'source 2']);
    Source::factory()->create(['name' => 'source 3']);

    Article::factory()->create(['source_id' => 1, 'category_id' => 1, 'author' => 'John Doe']);
    Article::factory()->create(['source_id' => 1, 'category_id' => 2, 'author' => 'Jane Doe']);
    Article::factory()->create(['source_id' => 2, 'category_id' => 1, 'author' => 'John Doe']);
    Article::factory()->create(['source_id' => 2, 'category_id' => 2, 'author' => 'Jane Doe']);
    Article::factory()->create(['source_id' => 3, 'category_id' => 1, 'author' => 'Alice Doe']);
    Article::factory()->create(['source_id' => 3, 'category_id' => 3, 'author' => 'Alice Doe']);
    Article::factory()->create(['source_id' => 3, 'category_id' => 3, 'author' => 'Alice Doe']);

    $token = $user->createToken('test-token')->plainTextToken;
    $this->withHeaders(['Authorization' => "Bearer $token"])
        ->getJson('/api/articles/prefered')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'slug',
                    'title',
                    'summary',
                    'published_at',
                    'category',
                    'author',
                    'source',
                ],
            ],
        ]);
});
