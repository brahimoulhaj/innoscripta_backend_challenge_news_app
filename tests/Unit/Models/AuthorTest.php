<?php

use App\Models\Article;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('to array', function () {
    $author = Author::factory()->create()->fresh();

    expect(array_keys($author->toArray()))->toBe([
        'id',
        'name',
    ]);
});

test('can be added to database', function () {
    $author = Author::factory()->create();

    expect(Author::count())->toBe(1);

    $this->assertDatabaseHas('authors', [
        'id' => $author->id,
        'name' => $author->name,
    ]);
});

test('has articles', function () {
    $author = Author::factory()->create();

    $article1 = Article::factory()->create(['author_id' => $author->id]);
    $article2 = Article::factory()->create(['author_id' => $author->id]);

    expect($author->articles()->count())->toBe(2);

    $this->assertDatabaseHas('articles', [
        'id' => $article1->id,
        'author_id' => $author->id
    ]);

    $this->assertDatabaseHas('articles', [
        'id' => $article2->id,
        'author_id' => $author->id
    ]);
});