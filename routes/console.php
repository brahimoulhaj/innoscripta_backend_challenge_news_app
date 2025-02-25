<?php

use App\Models\Article;
use App\Services\NewsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('news:fetch', function (NewsService $service) {
    $this->info('fetching articles...');

    try {
        $news = $service->fetchNews();
        foreach (collect($news)->chunk(100) as $chunk) {
            DB::transaction(function () use ($chunk) {
                DB::table('articles')->upsert($chunk->toArray(), uniqueBy: ['article_url']);
                $this->info(count($chunk).' saved');
            });
        }
    } catch (\Throwable $e) {
        $this->error($e->getMessage());
        $this->error($e->getTraceAsString());
    }

    $count = Article::count();
    $this->info("{$count} articles exists in the database.");
})->hourly();
