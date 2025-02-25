<?php

use App\Services\NewsService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('news:fetch', function (NewsService $service) {
    $this->info("fetching articles...");
    $service->fetchNews();
});
