<?php

namespace App\Services;

use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use App\Services\Sources\NewsApiSource;
use App\Services\Sources\NewYorkTimesApiSource;
use App\Services\Sources\TheGuardianApiSource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use function Laravel\Prompts\info;

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

            info($handler::class . " -- " . count($articles));
        }

        return $news;
    }

    private function resolveSourceHandler(Source $source): ?NewsSourceInterface
    {
        $handlers = [
            'the-guardian' => TheGuardianApiSource::class,
            'news-api' => NewsApiSource::class,
            'new-york-times' => NewYorkTimesApiSource::class
        ];

        $handlerClass = $handlers[Str::slug($source->name)] ?? null;

        if (! $handlerClass) {
            // throw new \Exception("No handler for source: {$source->name}");
            Log::error("No handler for source: {$source->name}");
            return null;
        }

        return new $handlerClass;
    }
}
