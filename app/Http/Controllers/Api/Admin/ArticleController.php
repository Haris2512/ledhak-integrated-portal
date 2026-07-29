<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
     * Store a newly created article.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'content' => ['required', 'string'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['Draft', 'Published'])],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(5);
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
    public function update(Request $request, Article $article): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($article->id)],
            'content' => ['sometimes', 'required', 'string'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(['Draft', 'Published'])],
        ]);

        if (isset($validated['title']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(5);
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
