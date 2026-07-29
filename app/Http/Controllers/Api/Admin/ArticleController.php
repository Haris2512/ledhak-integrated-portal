<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of all articles (including Drafts).
     */
    public function index(): JsonResponse
    {
        $articles = Article::latest()->paginate(15);

        return response()->json($articles);
    }

    /**
     * Store a newly created article with an automatically generated unique slug.
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Automatically generate unique slug from title if empty
        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $count = 1;

            while (Article::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $validated['slug'] = $slug;
        }

        $article = Article::create($validated);

        return response()->json([
            'message' => 'Berita/Artikel berhasil dibuat.',
            'data' => $article,
        ], 201);
    }

    /**
     * Display specified article detail.
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json([
            'data' => $article,
        ]);
    }

    /**
     * Update specified article.
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $validated = $request->validated();

        // Generate unique slug if title is modified and slug is not explicitly provided
        if (isset($validated['title']) && empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $count = 1;

            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $validated['slug'] = $slug;
        }

        $article->update($validated);

        return response()->json([
            'message' => 'Berita/Artikel berhasil diperbarui.',
            'data' => $article,
        ]);
    }

    /**
     * Remove specified article.
     */
    public function destroy(Article $article): JsonResponse
    {
        $articleTitle = $article->title;
        $article->delete();

        return response()->json([
            'message' => "Artikel \"{$articleTitle}\" berhasil dihapus.",
        ]);
    }
}
