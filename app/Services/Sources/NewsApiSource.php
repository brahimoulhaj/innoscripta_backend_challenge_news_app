<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiSource implements NewsSourceInterface
{
    private Source $source;

    private Category $category;

    public function fetch(Source $source, Category $category): array
    {
        $this->source = $source;
        $this->category = $category;
        $response = Http::get(url: $source->url.'/everything', query: [
            'apiKey' => $source->api_key,
            'q' => strtolower($category->name),
            'pageSize' => 100,
            'from' => now()->subDay()->format('Y-m-d'),
        ]);
        if (! $response->successful()) {
            Log::error('NewsApiSource::Error::'.$response->body());

            return [];
        }

        return $response->json('articles');
    }

    public function transform(array $data): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'slug' => str()->slug($article['title']).'-'.random_int(1000, 9999),
                'summary' => $article['description'] ?? $article['content'] ?? '',
                'article_url' => $article['url'],
                'image_url' => $article['urlToImage'],
                'published_at' => Carbon::parse($article['publishedAt']),
                'source_id' => $this->source->id,
                'category_id' => $this->category->id,
                'author' => $article['author'],
            ];
        }, $data);
    }
}
