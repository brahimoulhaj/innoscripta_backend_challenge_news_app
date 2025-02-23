<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('sources')->upsert([
            ['id' => 1, 'name' => 'The Guardian', 'url' => 'https://content.guardianapis.com', 'api_key' => encrypt('ca3a4eef-f7d8-4459-a4f9-7620d64ffee8', false)],
            ['id' => 2, 'name' => 'News API', 'url' => 'https://newsapi.org/v2', 'api_key' => encrypt('d0ffb4d37d1249479c5c39a3d7e1db97', false)],
        ], uniqueBy: "id");

        $categories = Category::factory(40)->create();     
        print("Created {$categories->count()} categories\n");   
        $authors = Author::factory(100)->create();
        print("Created {$authors->count()} authors\n");   

        foreach ($categories as $category) {
            $count = random_int(5, 100);
            Article::factory($count)->create([
                'author_id' => $authors->random()->id,
                'category_id' => $category->id
            ]);
            print("Created {$count} articles for {$category->name}\n");   
        }
    }
}
