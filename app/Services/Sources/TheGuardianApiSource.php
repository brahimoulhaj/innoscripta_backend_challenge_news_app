<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TheGuardianApiSource implements NewsSourceInterface
{
    private Source $source;

    private Category $category;

    public function fetch(Source $source, Category $category): array
    {
        $this->source = $source;
        $this->category = $category;
        $response = Http::get(url: $source->url.'/search', query: [
            'api-key' => $source->api_key,
            'q' => strtolower($category->name),
            'page-size' => 100,
            'from-date' => now()->subDay()->format('Y-m-d'),
            'show-fields' => 'thumbnail,headline',
            'show-references' => 'author',
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
                'summary' => $article['fields']['headline'] ?? $article['webTitle'],
                'article_url' => $article['webUrl'],
                'image_url' => $article['fields']['thumbnail'] ?? '',
                'published_at' => Carbon::parse($article['webPublicationDate']),
                'source_id' => $this->source->id,
                'category_id' => $this->category->id,
                'author' => 'unknown', // I didn't see any author field in the response, so I'm assuming it's "unknown"
            ];
        }, $data);
    }
}
