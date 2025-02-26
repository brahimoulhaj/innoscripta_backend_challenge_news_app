<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => 'string', 'filters' => 'string']);

        $articles = Article::with('source')
            ->filter($request->get('filters'))
            ->search($request->get('search'))
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
