<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Source;

it('can fetch articles', function () {
    Article::factory(20)->create();

    $this->getJson(route('articles.index'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'summary',
                    'article_url',
                    'image_url',
                    'published_at',
                    'source',
                    'category',
                    'author',
                ],
            ],
            'links',
            'meta',
        ]);
});

it('can search for articles', function () {
    Article::factory(5)->create();
    Article::factory(2)->create(['title' => 'my test title']);
    Article::factory(3)->create(['summary' => 'my test summary']);

    expect(Article::count())->toBe(10);

    $this->getJson(route('articles.index', ['search' => 'test title']))
        ->assertSuccessful()
        ->assertJsonCount(2, 'data');

    $this->getJson(route('articles.index', ['search' => 'test summary']))
        ->assertSuccessful()
        ->assertJsonCount(3, 'data');

    $this->getJson(route('articles.index', ['search' => 'my test']))
        ->assertSuccessful()
        ->assertJsonCount(5, 'data');
});

it('can filter articles by source, category and author', function () {
    Source::factory()->create(['name' => 'The Guardian']);
    Source::factory()->create(['name' => 'News API']);

    expect(Source::count())->toBe(2);

    $category1 = Category::factory()->create(['name' => 'Category 1']);
    $category2 = Category::factory()->create(['name' => 'Category 2']);
    $category3 = Category::factory()->create(['name' => 'Category 3']);
    $category4 = Category::factory()->create(['name' => 'Category 4']);

    Article::factory(1)->create(['source_id' => 1, 'category_id' => $category1->id, 'author' => 'Author 1']);
    Article::factory(3)->create(['source_id' => 1, 'category_id' => $category2->id, 'author' => 'Author 3']);
    Article::factory(2)->create(['source_id' => 2, 'category_id' => $category1->id, 'author' => 'Author 1']);
    Article::factory(4)->create(['source_id' => 2, 'category_id' => $category3->id, 'author' => 'Author 2']);
    Article::factory(5)->create();

    expect(Article::count())->toBe(15);

    $this->getJson(route('articles.index', [
        'filters' => 'source.name:The Guardian',
    ]))->assertJsonCount(4, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'source.name:News API',
    ]))->assertJsonCount(6, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'category.name:Category 1',
    ]))->assertJsonCount(3, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'category.name:Category 2',
    ]))->assertJsonCount(3, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'category.name:Category 1,Category 2',
    ]))->assertJsonCount(6, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'author:Author 1',
    ]))->assertJsonCount(3, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'author:Author 2',
    ]))->assertJsonCount(4, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'source.name:The Guardian;category.name:Category 1;author:Author 1',
    ]))->assertJsonCount(1, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'source.name:News API;category.name:Category 3;author:Author 2',
    ]))->assertJsonCount(4, 'data');
});

it('can filter articles by date range', function () {
    Article::factory(5)->create(['published_at' => now()->subDays(2)]);
    Article::factory(3)->create(['published_at' => now()->subDays(5)]);

    expect(Article::count())->toBe(8);

    $this->getJson(route('articles.index', [
        'filters' => 'published_at:'.now()->subDays(3)->format('Y-m-d'),
    ]))->assertJsonCount(5, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'published_at:'.now()->subDays(7)->format('Y-m-d').','.now()->subDays(4)->format('Y-m-d'),
    ]))->assertJsonCount(3, 'data');

    $this->getJson(route('articles.index', [
        'filters' => 'published_at:'.now()->format('Y-m-d').','.now()->format('Y-m-d'),
    ]))->assertJsonCount(0, 'data');
});
