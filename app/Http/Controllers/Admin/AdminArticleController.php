<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::with('author')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('content', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'thumbnail_type' => ['nullable', Rule::in(['upload', 'link'])],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'thumbnail_link' => ['nullable', 'url', 'max:255'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'archive'])],
            'published_at' => ['nullable', 'date'],
        ]);

        $slug = $this->makeUniqueSlug($validated['title']);
        $thumbnail = null;
        $thumbnailType = $request->input('thumbnail_type', 'upload');
        $publishedAt = $validated['published_at'] ?? null;

        if (($validated['status'] ?? 'draft') === 'published' && empty($publishedAt)) {
            $publishedAt = now();
        }

        if ($thumbnailType === 'upload' && $request->hasFile('thumbnail_file')) {
            $thumbnail = $request->file('thumbnail_file')->store('img/articles', 'public');
        }

        if ($thumbnailType === 'link' && $request->filled('thumbnail_link')) {
            $thumbnail = $request->thumbnail_link;
        }

        Article::create([
            'author_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'thumbnail' => $thumbnail,
            'views' => 0,
            'status' => $validated['status'] ?? 'draft',
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel kesehatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string'],
            'thumbnail_type' => ['nullable', Rule::in(['upload', 'link'])],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'thumbnail_link' => ['nullable', 'url', 'max:255'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'archive'])],
            'published_at' => ['nullable', 'date'],
        ]);

        $data = [
            'title' => $validated['title'] ?? $article->title,
            'content' => $validated['content'] ?? $article->content,
            'status' => $validated['status'] ?? $article->status,
            'published_at' => array_key_exists('published_at', $validated)
                ? $validated['published_at']
                : $article->published_at,
        ];

        if (isset($validated['title']) && $validated['title'] !== $article->title) {
            $data['slug'] = $this->makeUniqueSlug($validated['title'], $article->id);
        }

        if (
            ($validated['status'] ?? $article->status) === 'published' &&
            empty($validated['published_at']) &&
            is_null($article->published_at)
        ) {
            $data['published_at'] = now();
        }

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

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('success', 'Artikel kesehatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if ($article->thumbnail && !Str::startsWith($article->thumbnail, ['http://', 'https://'])) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel kesehatan berhasil dihapus.');
    }

    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Article::when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}