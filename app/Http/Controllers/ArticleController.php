<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    // ============================================================
    // Member-facing (Blade view, hanya artikel published dan sudah waktunya tayang)
    // ============================================================

    public function welcome()
    {
        $latestArticles = Article::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredDoctors = Doctor::with('user')
            ->where('status', 'active')
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $totalDoctors = Doctor::where('status', 'active')->count();

        return view('member.welcome', compact('latestArticles', 'featuredDoctors', 'totalDoctors'));
    }

    public function publicIndex(Request $request)
    {
        $articles = Article::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('author:id,name')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->search}%");
            })
            ->latest('published_at')
            ->paginate(9);

        return view('member.articles.index', compact('articles'));
    }

    public function publicShow(Article $article)
    {
        abort_unless(
            $article->status === 'published'
            && !is_null($article->published_at)
            && $article->published_at <= now(),
            404
        );

        $article->increment('views');

        return view('member.articles.show', compact('article'));
    }

    // ============================================================
    // JSON / CRUD
    // ============================================================

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
            'views' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'archive'])],
            'published_at' => ['nullable', 'date'],
            'thumbnail_type' => ['nullable', Rule::in(['upload', 'link'])],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'thumbnail_link' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['slug'] = $validated['slug'] ?? $this->makeUniqueSlug($validated['title']);

        if (($validated['status'] ?? 'draft') === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $thumbnail = null;
        $thumbnailType = $request->input('thumbnail_type', 'upload');

        if ($thumbnailType === 'upload' && $request->hasFile('thumbnail_file')) {
            $thumbnail = $request->file('thumbnail_file')->store('img/articles', 'public');
        }

        if ($thumbnailType === 'link' && $request->filled('thumbnail_link')) {
            $thumbnail = $request->thumbnail_link;
        }

        $article = Article::create([
            'author_id' => $validated['author_id'],
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnail,
            'views' => $validated['views'] ?? 0,
            'status' => $validated['status'] ?? 'draft',
            'published_at' => $validated['published_at'] ?? null,
        ]);

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
            'views' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'archive'])],
            'published_at' => ['nullable', 'date'],
            'thumbnail_type' => ['nullable', Rule::in(['upload', 'link'])],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'thumbnail_link' => ['nullable', 'url', 'max:255'],
        ]);

        if (array_key_exists('slug', $validated) && empty($validated['slug'])) {
            $validated['slug'] = $this->makeUniqueSlug($validated['title'] ?? $article->title, $article->id);
        }

        if (
            ($validated['status'] ?? $article->status) === 'published'
            && empty($validated['published_at'])
            && is_null($article->published_at)
        ) {
            $validated['published_at'] = now();
        }

        $data = [
            'author_id' => $validated['author_id'] ?? $article->author_id,
            'title' => $validated['title'] ?? $article->title,
            'slug' => $validated['slug'] ?? $article->slug,
            'content' => $validated['content'] ?? $article->content,
            'views' => $validated['views'] ?? $article->views,
            'status' => $validated['status'] ?? $article->status,
            'published_at' => array_key_exists('published_at', $validated)
                ? $validated['published_at']
                : $article->published_at,
        ];

        $thumbnailType = $request->input('thumbnail_type');

        if ($thumbnailType === 'upload' && $request->hasFile('thumbnail_file')) {
            if ($article->thumbnail && !Str::startsWith($article->thumbnail, ['http://', 'https://'])) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail_file')->store('img/articles', 'public');
        }

        if ($thumbnailType === 'link' && $request->filled('thumbnail_link')) {
            if ($article->thumbnail && !Str::startsWith($article->thumbnail, ['http://', 'https://'])) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            $data['thumbnail'] = $request->thumbnail_link;
        }

        $article->update($data);

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

        while (
            Article::withTrashed()
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}   