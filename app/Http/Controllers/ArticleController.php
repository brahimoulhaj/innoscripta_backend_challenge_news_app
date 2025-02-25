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
        if(request()->has('from_date')) {
            dd("yes");
        }
        $articles = Article::with(['category', 'author', 'source'])
            ->when(request()->filled('search'), function ($query) {
                $query->whereLike('title', "%" . request('search') . "%")
                    ->orWhereLike('summary', "%" . request('search') . "%");
            })
            ->when(request()->filled('category'), function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('name', request('category'));
                });
            })
            ->when(request()->filled('author'), function ($query) {
                $query->whereHas('author', function ($query) {
                    $query->where('name', request('author'));
                });
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
