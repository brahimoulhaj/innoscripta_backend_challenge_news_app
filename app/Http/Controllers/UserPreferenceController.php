<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePreferencesRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;

class UserPreferenceController extends Controller
{

    public function show()
    {
        $user = request()->user();
        return response()->json([
            'data' => $user->preferences
        ]);
    }

    public function edit()
    {
        $categories = Category::get(['id', 'name']);
        $sources = Source::get(['id', 'name']);
        $authors = Article::pluck('author')->unique()->values()->all();

        return response()->json(['data' => [
            'categories' => $categories,
            'sources' => $sources,
            'authors' => $authors,
        ]]);
    }

    public function update(UpdatePreferencesRequest $request)
    {
        $user = $request->user();
        $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_sources' => $request->sources,
                'preferred_categories' => $request->categories,
                'preferred_authors' => $request->authors,
            ]
        );

        return response()->json(['message' => 'Preferences saved']);
    }
}
