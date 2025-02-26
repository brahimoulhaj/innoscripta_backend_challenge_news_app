<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticlePreferenceController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['search' => 'string', 'filters' => 'string']);

        $preferences = $request->user()->preferences;

        $articles = Article::with('source')
            ->where(function ($query) use ($preferences) {
                $query
                    ->whereIn('source_id', $preferences->preferred_sources ?? [])
                    ->orWhereIn('category_id', $preferences->preferred_categories ?? [])
                    ->orWhereIn('author', $preferences->preferred_authors ?? []);
            })
            ->filter($request->get('filters'))
            ->search($request->get('search'))
            ->orderByDesc('published_at')
            ->paginate()
            ->withQueryString();

        return ArticleResource::collection($articles);
    }
}
