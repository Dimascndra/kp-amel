<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::query();

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('tag', 'like', "%{$s}%")
                  ->orWhere('author', 'like', "%{$s}%");
            });
        }

        $articles = $query->latest('published_at')->paginate(10)->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'tag' => ['required', 'string', 'max:100'],
            'tag_color' => ['required', 'in:blue,amber,red,emerald,purple'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:100'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'published_at' => ['required', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $img = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $img->getClientOriginalExtension();
            $destPath = public_path('uploads/articles');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $img->move($destPath, $filename);
            $validated['image'] = '/uploads/articles/' . $filename;
        }

        $validated['slug'] = Str::slug($request->input('title'));
        $counter = 1;
        while (Article::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = Str::slug($request->input('title')) . '-' . $counter++;
        }

        $validated['author'] = $request->input('author') ?: 'Tim Redaksi Bank Majalengka';
        $validated['read_time'] = $request->input('read_time') ?: '3 menit baca';
        $validated['is_published'] = $request->has('is_published');

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel '{$validated['title']}' berhasil diterbitkan!");
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'tag' => ['required', 'string', 'max:100'],
            'tag_color' => ['required', 'in:blue,amber,red,emerald,purple'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:100'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'published_at' => ['required', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $img = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $img->getClientOriginalExtension();
            $destPath = public_path('uploads/articles');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $img->move($destPath, $filename);
            $validated['image'] = '/uploads/articles/' . $filename;
        }

        $validated['author'] = $request->input('author') ?: 'Tim Redaksi Bank Majalengka';
        $validated['read_time'] = $request->input('read_time') ?: '3 menit baca';
        $validated['is_published'] = $request->has('is_published');

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel '{$article->title}' berhasil diperbarui!");
    }

    public function destroy(Article $article): RedirectResponse
    {
        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel '{$title}' telah dihapus.");
    }

    public function toggleStatus(Article $article): RedirectResponse
    {
        $article->update(['is_published' => !$article->is_published]);

        $status = $article->is_published ? 'diterbitkan' : 'disembunyikan';
        return back()->with('success', "Status artikel berhasil {$status}.");
    }
}
