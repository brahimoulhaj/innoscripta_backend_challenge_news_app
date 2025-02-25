<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TODO: find a secure way to write api keys here in the file
        DB::table('sources')->upsert([
            ['id' => 1, 'name' => 'The Guardian', 'url' => 'https://content.guardianapis.com', 'api_key' => encrypt('ca3a4eef-f7d8-4459-a4f9-7620d64ffee8', false)],
            ['id' => 2, 'name' => 'News API', 'url' => 'https://newsapi.org/v2', 'api_key' => encrypt('d0ffb4d37d1249479c5c39a3d7e1db97', false)],
            ['id' => 3, 'name' => 'New York Times', 'url' => 'https://api.nytimes.com/svc/search/v2', 'api_key' => encrypt('G6H2RLuRyYbENJjzMdVQdesCTzSjLbbl', false)],
        ], uniqueBy: 'id');
    }
}
