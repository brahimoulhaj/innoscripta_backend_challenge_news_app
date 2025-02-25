<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'preferred_sources' => 'array',
            'preferred_sources.*' => 'exists:sources,id',
            'preferred_categories' => 'array',
            'preferred_categories.*' => 'exists:categories,id',
            'preferred_authors' => 'array',
            'preferred_authors.*' => 'exists:authors,id',
        ]);

        $user = $request->user();
        $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_sources' => $request->preferred_sources,
                'preferred_categories' => $request->preferred_categories,
                'preferred_authors' => $request->preferred_authors,
            ]
        );

        return response()->json(['message' => 'Preferences saved']);
    }
}
