<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticlePreferenceController extends Controller
{
    public function __invoke()
    {
        $preferences = request()->user()->preferences;

        $articles = Article::with('source')
            ->where(function ($query) use ($preferences) {
                $query
                    ->whereIn('source_id', $preferences->preferred_sources ?? [])
                    ->orWhereIn('category_id', $preferences->preferred_categories ?? [])
                    ->orWhereIn('author', $preferences->preferred_authors ?? []);
            })
            ->filter(request('filters'))
            ->search(request('search'))
            ->orderByDesc('published_at')
            ->paginate()
            ->withQueryString();

        return ArticleResource::collection($articles);
    }
}
