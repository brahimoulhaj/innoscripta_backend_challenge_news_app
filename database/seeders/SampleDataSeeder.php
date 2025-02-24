<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::factory(40)->create();
        print("Created {$categories->count()} categories\n");
        $authors = Author::factory(100)->create();
        print("Created {$authors->count()} authors\n");

        foreach ($categories as $category) {
            $count = random_int(5, 100);
            Article::factory($count)->create([
                'author_id' => $authors->random()->id,
                'category_id' => $category->id,
                'source_id' => random_int(1, 2)
            ]);
            print("Created {$count} articles for {$category->name}\n");
        }
    }
}
