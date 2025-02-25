<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('to array', function () {
    $article = Article::factory()->create()->fresh();

    expect(array_keys($article->toArray()))->toBe([
        'id',
        'title',
        'slug',
        'summary',
        'article_url',
        'image_url',
        'published_at',
        'source_id',
        'category',
        'author',
        'created_at',
        'updated_at',
    ]);
});

test('can be added to database', function () {
    $article = Article::factory()->create();

    expect(Article::count())->toBe(1);

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => $article->title,
        'summary' => $article->summary,
        'article_url' => $article->article_url,
        'image_url' => $article->image_url,
        'published_at' => $article->published_at,
        'source_id' => $article->source_id,
        'category' => $article->category,
        'author' => $article->author,
    ]);
});

test('belongs to source', function () {
    $article = Article::factory()->create()->fresh();

    expect(value: $article->source)->not->toBeNull();

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'source_id' => $article->source->id,
    ]);
});
