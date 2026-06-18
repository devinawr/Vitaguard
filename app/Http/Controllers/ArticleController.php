<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $articles = Article::query()
            ->with('author:id,name,email')
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', "%{$request->search}%"))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json($articles);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'author_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'views' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'Archive'])],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = $validated['slug'] ?? $this->makeUniqueSlug($validated['title']);

        if (($validated['status'] ?? 'draft') === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        return response()->json([
            'message' => 'Artikel berhasil dibuat.',
            'data' => $article->load('author:id,name,email'),
        ], 201);
    }

    public function show(Article $article): JsonResponse
    {
        $article->increment('views');

        return response()->json([
            'data' => $article->fresh()->load('author:id,name,email'),
        ]);
    }

    public function update(Request $request, Article $article): JsonResponse
    {
        $validated = $request->validate([
            'author_id' => ['sometimes', 'required', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($article->id),
            ],
            'content' => ['sometimes', 'required', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'views' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'Archive'])],
            'published_at' => ['nullable', 'date'],
        ]);

        if (array_key_exists('slug', $validated) && empty($validated['slug'])) {
            $validated['slug'] = $this->makeUniqueSlug($validated['title'] ?? $article->title, $article->id);
        }

        if (($validated['status'] ?? $article->status) === 'published'
            && empty($validated['published_at'])
            && is_null($article->published_at)) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return response()->json([
            'message' => 'Artikel berhasil diperbarui.',
            'data' => $article->fresh()->load('author:id,name,email'),
        ]);
    }

    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json([
            'message' => 'Artikel berhasil dihapus secara soft delete.',
        ]);
    }

    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Article::withTrashed()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
