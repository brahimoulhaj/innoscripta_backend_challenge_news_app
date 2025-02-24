<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('to array', function () {
    $category = Category::factory()->create()->fresh();

    expect(array_keys($category->toArray()))->toBe([
        'id',
        'name',
        'slug'
    ]);
});

test('can be added to database', function () {
    $category = Category::factory()->create();

    expect(Category::count())->toBe(1);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => $category->name,
        'slug' => $category->slug
    ]);
});

test('has articles', function () {
    $category = Category::factory()->create();

    $article1 = Article::factory()->create(['category_id' => $category->id]);
    $article2 = Article::factory()->create(['category_id' => $category->id]);

    expect($category->articles()->count())->toBe(2);

    $this->assertDatabaseHas('articles', [
        'id' => $article1->id,
        'category_id' => $category->id
    ]);

    $this->assertDatabaseHas('articles', [
        'id' => $article2->id,
        'category_id' => $category->id
    ]);
});
