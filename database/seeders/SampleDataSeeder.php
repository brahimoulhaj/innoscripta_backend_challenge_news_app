<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::factory(400)->create(['source_id' => random_int(1, 2)]);
    }
}
