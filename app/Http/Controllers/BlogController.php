<?php
// ═══════════════════════════════════════════════════════════
// app/Http/Controllers/BlogController.php
// ═══════════════════════════════════════════════════════════

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->with(['author', 'category', 'tags'])
            ->orderByDesc('published_at');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }

        // Search
        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('excerpt', 'like', "%{$s}%");
            });
        }

        $posts = $query->paginate(12)->withQueryString();

        $blogCategories = BlogCategory::withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();

        $popularTags = BlogTag::withCount(['posts' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->take(20)
            ->get();

        // CORRECT
        $recentPosts = BlogPost::published()
            ->orderByDesc('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'published_at', 'featured_image_id']);

        return view('blog.index', compact('posts', 'blogCategories', 'popularTags', 'recentPosts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        $post->increment('views');

        // Previous / Next
        $prev = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first(['id', 'title', 'slug']);

        $next = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first(['id', 'title', 'slug']);

        // Related posts — same category or shared tag
        $relatedIds = collect();
        if ($post->blog_category_id) {
            $relatedIds = $relatedIds->merge(
                BlogPost::published()
                    ->where('blog_category_id', $post->blog_category_id)
                    ->where('id', '!=', $post->id)
                    ->pluck('id')
            );
        }
        if ($post->tags->count()) {
            $relatedIds = $relatedIds->merge(
                BlogPost::published()
                    ->whereHas('tags', fn($q) => $q->whereIn('blog_tags.id', $post->tags->pluck('id')))
                    ->where('id', '!=', $post->id)
                    ->pluck('id')
            );
        }

        $related = BlogPost::published()
            ->whereIn('id', $relatedIds->unique()->take(10))
            ->with(['author', 'category'])
            ->take(3)
            ->get();

        // CORRECT
        $recentPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->take(4)
            ->get(['id', 'title', 'slug', 'published_at', 'featured_image_id']);

        $popularTags = BlogTag::withCount(['posts' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->take(15)
            ->get();

        return view('blog.show', compact(
            'post', 'prev', 'next', 'related', 'recentPosts', 'popularTags'
        ));
    }

    // Blog category archive
    public function category(string $slug)
    {
        $blogCategory = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::published()
            ->where('blog_category_id', $blogCategory->id)
            ->with(['author', 'tags'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $blogCategories = BlogCategory::withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();

        return view('blog.index', compact('posts', 'blogCategory', 'blogCategories'));
    }

    // Blog tag archive
    public function tag(string $slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::published()
            ->whereHas('tags', fn($q) => $q->where('slug', $slug))
            ->with(['author', 'category'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $blogCategories = BlogCategory::withCount(['posts' => fn($q) => $q->published()])
            ->orderBy('name')
            ->get();

        return view('blog.index', compact('posts', 'tag', 'blogCategories'));
    }
}
