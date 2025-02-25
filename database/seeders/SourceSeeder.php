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
        $sources = [];

        if (!empty(env('THE_GUARDIAN_API_KEY'))) {
            $sources[] = [
                'id' => 1,
                'name' => 'The Guardian',
                'url' => 'https://content.guardianapis.com',
                'api_key' => encrypt(env('THE_GUARDIAN_API_KEY'), false)
            ];
        }

        if (!empty(env('NEWS_API_API_KEY'))) {
            $sources[] = [
                'id' => 2,
                'name' => 'News API',
                'url' => 'https://newsapi.org/v2',
                'api_key' => encrypt(env('NEWS_API_API_KEY'), false)
            ];
        }

        if (!empty(env('NEW_YORK_TIMES_API_KEY'))) {
            $sources[] = [
                'id' => 3,
                'name' => 'New York Times',
                'url' => 'https://api.nytimes.com/svc/search/v2',
                'api_key' => encrypt(env('NEW_YORK_TIMES_API_KEY'), false)
            ];
        }

        if (count(value: $sources) === 0) {
            throw new \Exception('Please set at least one of the following environment variables: THE_GUARDIAN_API_KEY, NEWS_API_API_KEY, NEW_YORK_TIMES_API_KEY');
        }

        DB::table('sources')->upsert($sources, uniqueBy: 'id');
    }
}
