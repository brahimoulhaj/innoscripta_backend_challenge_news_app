<?php

namespace App\Services;

use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use App\Services\Sources\NewsApiSource;
use Illuminate\Support\Str;

class NewsService
{

    public function fetchNews()
    {
        $sources = Source::all();
        foreach ($sources as $source) {
            $handler = $this->resolveSourceHandler($source);
            if(!$handler) continue;
            $data = $handler->fetch($source);
            $articles = $handler->transform($data);
            dd($articles);
        }
    }

    private function resolveSourceHandler(Source $source): ?NewsSourceInterface
    {
        $handlers = [
            'news-api' => NewsApiSource::class,
            // 'The Guardian' => GuardianSource::class,
            // 'New York Times' => NewYorkTimesSource::class
        ];

        $handlerClass = $handlers[Str::slug($source->name)] ?? null;

        if (!$handlerClass) {
            return null;
            // throw new \Exception("No handler for source: {$source->name}");
        }

        return new $handlerClass();
    }
}
