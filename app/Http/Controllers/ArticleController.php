<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('source')
            ->when(request()->filled('search'), function ($query) {
                $query->whereLike('title', '%'.request('search').'%')
                    ->orWhereLike('summary', '%'.request('search').'%');
            })
            ->when(request()->filled('category'), function ($query) {
                $query->where('category', request('category'));
            })
            ->when(request()->filled('author'), function ($query) {
                $query->where('author', request('author'));
            })
            ->when(request()->filled('source'), function ($query) {
                $query->whereHas('source', function ($query) {
                    $query->where('name', request('source'));
                });
            })
            ->when(request()->filled('from-date'), function ($query) {
                $query->whereDate('published_at', '>=', request('from-date'));
            })
            ->when(request()->filled('to-date'), function ($query) {
                $query->whereDate('published_at', '<=', request('to-date'));
            })
            ->orderByDesc('published_at')
            ->paginate()
            ->withQueryString();

        return ArticleResource::collection($articles);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['category', 'author', 'source']);

        return new ArticleResource($article);
    }
}
