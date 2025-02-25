<?php

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Database\Seeders\SourceSeeder;

it('can fetch articles', function () {
    Article::factory(20)->create();

    $this->getJson(route('articles.index'))
        ->assertSuccessful()
        ->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "title",
                    "slug",
                    "summary",
                    "article_url",
                    "image_url",
                    "published_at",
                    "source",
                    "category",
                    "author",
                ]
            ],
            "links",
            "meta"
        ]);
});

it('can search for articles', function () {
    Article::factory(5)->create();
    Article::factory(2)->create(['title' => 'my test title']);
    Article::factory(3)->create(['summary' => 'my test summary']);

    expect(Article::count())->toBe(10);

    $this->getJson(route('articles.index', ['search' => 'test title']))
        ->assertSuccessful()
        ->assertJsonCount(2, "data");

    $this->getJson(route('articles.index', ['search' => 'test summary']))
        ->assertSuccessful()
        ->assertJsonCount(3, "data");

    $this->getJson(route('articles.index', ['search' => 'my test']))
        ->assertSuccessful()
        ->assertJsonCount(5, "data");
});

it('can filter articles by source, category and author', function () {
    $this->seed(SourceSeeder::class); // The Guardian -> id:1, News API -> id:2

    expect(Source::count())->toBe(2);

    Category::factory()->create(['name' => 'Category 1']);
    Category::factory()->create(['name' => 'Category 2']);
    Category::factory()->create(['name' => 'Category 3']);
    Category::factory()->create(['name' => 'Category 4']);

    Author::factory()->create(['name' => 'Author 1']);
    Author::factory()->create(['name' => 'Author 2']);
    Author::factory()->create(['name' => 'Author 3']);
    Author::factory()->create(['name' => 'Author 4']);

    Article::factory(1)->create(['source_id' => 1, 'category_id' => 1, 'author_id' => 1]);
    Article::factory(3)->create(['source_id' => 1, 'category_id' => 2, 'author_id' => 3]);
    Article::factory(2)->create(['source_id' => 2, 'category_id' => 1, 'author_id' => 1]);
    Article::factory(4)->create(['source_id' => 2, 'category_id' => 3, 'author_id' => 2]);
    Article::factory(5)->create();

    expect(Article::count())->toBe(15);

    $this->getJson(route('articles.index', ['source' => 'The Guardian']))->assertJsonCount(4, "data");
    $this->getJson(route('articles.index', ['source' => 'News API']))->assertJsonCount(6, "data");

    $this->getJson(route('articles.index', ['category' => 'Category 1']))->assertJsonCount(3, "data");
    $this->getJson(route('articles.index', ['category' => 'Category 2']))->assertJsonCount(3, "data");
    $this->getJson(route('articles.index', ['category' => 'Category 3']))->assertJsonCount(4, "data");
    $this->getJson(route('articles.index', ['category' => 'Category 4']))->assertJsonCount(0, "data");

    $this->getJson(route('articles.index', ['author' => 'Author 1']))->assertJsonCount(3, "data");
    $this->getJson(route('articles.index', ['author' => 'Author 2']))->assertJsonCount(4, "data");
    $this->getJson(route('articles.index', ['author' => 'Author 3']))->assertJsonCount(3, "data");
    $this->getJson(route('articles.index', ['author' => 'Author 4']))->assertJsonCount(0, "data");

    $this->getJson(route('articles.index', ['source' => 'The Guardian', 'category' => 'Category 1', 'author' => 'Author 1']))
        ->assertJsonCount(1, "data");
    $this->getJson(route('articles.index', ['source' => 'News API', 'category' => 'Category 3', 'author' => 'Author 2']))
        ->assertJsonCount(4, "data");
});

it('can filter articles by date range', function () {
    Article::factory(5)->create(["published_at" => now()->subDays(2)]);
    Article::factory(3)->create(["published_at" => now()->subDays(5)]);

    expect(Article::count())->toBe(8);

    $this->getJson(route('articles.index', [
        'from-date' => now()->subDays(3)->format('Y-m-d')
    ]))->assertJsonCount(5, "data");

    $this->getJson(route('articles.index', [
        'from-date' => now()->subDays(7)->format('Y-m-d'),
        'to-date' => now()->subDays(4)->format('Y-m-d')
    ]))->assertJsonCount(3, "data");

    $this->getJson(route('articles.index', [
        'from-date' => now()->format('Y-m-d'),
        'to-date' => now()->format('Y-m-d')
    ]))->assertJsonCount(0, "data");
});
