<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Get published articles list for public view.
     */
    public function index(): JsonResponse
    {
        $articles = Article::where('status', 'Published')
            ->latest()
            ->paginate(10);

        return response()->json($articles);
    }

    /**
     * Get published article detail by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'Published')
            ->firstOrFail();

        return response()->json([
            'data' => $article,
        ]);
    }
}
