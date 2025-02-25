<?php

use App\Models\Article;
use App\Services\NewsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('news:fetch', function (NewsService $service) {
    DB::transaction(function () use ($service) {
        $this->info('fetching articles...');
        $news = $service->fetchNews();
        $this->info('articles fetched');
        $this->info('saving articles');
        foreach (collect($news)->chunk(100) as $chunk) {
            DB::table('articles')->upsert($chunk->toArray(), uniqueBy: ['article_url']);
            $this->info(count($chunk) . ' saved');
        }
        $count = Article::count();
        $this->info("articles count: {$count}");
    });
});
