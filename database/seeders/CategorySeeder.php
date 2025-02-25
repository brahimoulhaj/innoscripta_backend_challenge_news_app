<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->upsert([
            ['id' => 1, 'name' => 'technology', 'slug' => 'technology'],
            ['id' => 2, 'name' => 'business', 'slug' => 'business'],
            ['id' => 3, 'name' => 'entertainment', 'slug' => 'entertainment'],
            ['id' => 4, 'name' => 'health', 'slug' => 'health'],
            ['id' => 5, 'name' => 'science', 'slug' => 'science'],
            ['id' => 6, 'name' => 'sports', 'slug' => 'sports'],
        ], uniqueBy: 'id');
    }
}
