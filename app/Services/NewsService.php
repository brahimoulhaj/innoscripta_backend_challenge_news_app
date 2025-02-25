<?php

namespace App\Services;

use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use App\Services\Sources\NewsApiSource;
use App\Services\Sources\NewYorkTimesApiSource;
use App\Services\Sources\TheGuardianApiSource;
use Illuminate\Support\Str;

class NewsService
{
    public function fetchNews()
    {
        $sources = Source::all();

        $news = [];
        foreach ($sources as $source) {
            $handler = $this->resolveSourceHandler($source);
            if (! $handler) {
                continue;
            }
            $data = $handler->fetch($source, 'technology');
            $articles = $handler->transform($data);
            $news = array_merge($news, $articles);
        }

        return $news;
    }

    private function resolveSourceHandler(Source $source): ?NewsSourceInterface
    {
        $handlers = [
            'news-api' => NewsApiSource::class,
            'the-guardian' => TheGuardianApiSource::class,
            'new-york-times' => NewYorkTimesApiSource::class
        ];

        $handlerClass = $handlers[Str::slug($source->name)] ?? null;

        if (! $handlerClass) {
            return null;
            // throw new \Exception("No handler for source: {$source->name}");
        }

        return new $handlerClass;
    }
}
