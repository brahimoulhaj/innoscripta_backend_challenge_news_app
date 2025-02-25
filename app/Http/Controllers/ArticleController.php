<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('source')
            ->filter(request('filters'))
            ->search(request('search'))
            ->orderByDesc('published_at')
            ->paginate()
            ->withQueryString();

        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        $article->load(['category', 'author', 'source']);

        return new ArticleResource($article);
    }
}
