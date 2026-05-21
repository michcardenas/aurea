<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogAdminController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::latest()->paginate(15);

        return view('admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'focus_keyword' => 'nullable|string|max:255',
            'category' => 'nullable|in:salud-visual,luz-azul,habitos,lentes',
            'canonical_url' => 'nullable|url|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'author_name' => 'nullable|string|max:255',
            'schema_type' => 'nullable|in:Article,BlogPosting,NewsArticle',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }

        // Auto-set published_at if publishing and no date set
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $validated['author_name'] = $validated['author_name'] ?: 'nuvion glass';
        $validated['schema_type'] = $validated['schema_type'] ?: 'BlogPosting';

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Artículo creado.');
    }

    public function show(BlogPost $blog): View
    {
        return view('admin.blog.show', ['post' => $blog]);
    }

    public function edit(BlogPost $blog): View
    {
        return view('admin.blog.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'focus_keyword' => 'nullable|string|max:255',
            'category' => 'nullable|in:salud-visual,luz-azul,habitos,lentes',
            'canonical_url' => 'nullable|url|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'author_name' => 'nullable|string|max:255',
            'schema_type' => 'nullable|in:Article,BlogPosting,NewsArticle',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }

        // Auto-set published_at if publishing for the first time
        if ($validated['status'] === 'published' && empty($validated['published_at']) && ! $blog->published_at) {
            $validated['published_at'] = now();
        }

        $validated['author_name'] = $validated['author_name'] ?: 'nuvion glass';
        $validated['schema_type'] = $validated['schema_type'] ?: 'BlogPosting';

        $blog->update($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Artículo actualizado.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Artículo eliminado.');
    }
}
