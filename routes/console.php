<?php

use App\Models\Article;
use App\Services\NewsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('news:fetch', function (NewsService $service) {
    DB::transaction(function () use ($service) {
        $this->info('fetching articles...');
        $news = $service->fetchNews();
        DB::table('articles')->upsert($news, uniqueBy: ['article_url']);
        $this->info('articles fetched');
        $count = Article::count();
        $this->info("articles count: {$count}");
    });
});
