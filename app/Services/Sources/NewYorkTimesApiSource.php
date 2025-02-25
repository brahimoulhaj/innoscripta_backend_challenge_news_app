<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Uri;

class NewYorkTimesApiSource implements NewsSourceInterface
{
    private Source $source;
    private string $category;

    public function fetch(Source $source, string $category): array
    {
        $this->source = $source;
        $this->category = $category;

        $page = 0;
        $data = [];

        while ($page < 10) {
            $response = Http::get(url: $source->url . '/articlesearch.json', query: [
                'api-key' => $source->api_key,
                'q' => $this->category,
                'page' => $page,
                'begin_date' => now()->subDay()->format('Ymd'),
            ]);

            if (! $response->successful()) {
                Log::error('NewYorkTimesApiSource::Error::' . $response->body());
            } else {
                $docs = $response->json('response.docs');
                if (count($docs) === 0) break;
                $data = array_merge($data, $docs);
            }

            $page++;
        }

        return $data;
    }

    public function transform(array $data): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['headline']['main'],
                'slug' => str()->slug($article['headline']['main']) . '-' . random_int(1000, 9999),
                'summary' => $article['snippet'] ?? $article['abstract'] ?? "",
                'article_url' => $article['web_url'],
                'image_url' => count($article['multimedia']) === 0 ? null : (Uri::of($article['web_url'])->host() . "/" . $article['multimedia'][0]['url']),
                'published_at' => $article['pub_date'],
                'source_id' => $this->source->id,
                'category' => $this->category,
                'author' => $article['byline']['original'],
            ];
        }, $data);
    }
}
