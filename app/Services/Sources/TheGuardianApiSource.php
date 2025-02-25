<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TheGuardianApiSource implements NewsSourceInterface
{
    private Source $source;

    public function fetch(Source $source, string $category): array
    {
        $this->source = $source;
        $response = Http::get(url: $source->url.'/everything', query: [
            'api-key' => $source->api_key,
            'q' => $category,
            'page-size' => 100,
            'from-date' => now()->subDay()->format('Y-m-d'),
            'show-fields' => 'thumbnail,body,headline',
            'show-references' => 'author'
        ]);
        if (! $response->successful()) {
            Log::error('TheGuardianApiSource::Error::'.$response->body());
            return [];
        }

        return $response->json('response.results');
    }

    public function transform(array $data): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['webTitle'],
                'slug' => str()->slug($article['webTitle']).'-'.random_int(1000, 9999),
                'summary' => $article['fields']['body'] ?? $article['webTitle'],
                'article_url' => $article['webUrl'],
                'image_url' => $article['fields']['thumbnail'],
                'published_at' => $article['webPublicationDate'],
                'source_id' => $this->source->id,
                'category' => $article['sectionName'],
                'author' => $article['references'],
            ];
        }, $data);
    }
}
