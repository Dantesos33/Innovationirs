<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService)
    {}

    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'category'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->category_id);
        }

        $posts      = $query->paginate(20)->withQueryString();
        $categories = BlogCategory::active()->get();

        return view('admin.blog.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::active()->orderBy('name')->get();
        $tags       = BlogTag::orderBy('name')->get();
        $authors    = \App\Models\Admin::active()->orderBy('name')->get();

        return view('admin.blog.create', compact('categories', 'tags', 'authors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:300',
            'slug'             => 'nullable|string|max:300|unique:blog_posts,slug',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'status'           => 'required|in:draft,published,scheduled',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tag_ids'          => 'nullable|array',
            'tag_ids.*'        => 'exists:blog_tags,id',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['admin_id'] = Auth::guard('admin')->id();

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $media                     = $this->mediaService->upload($request->file('featured_image'), 'blog');
            $data['featured_image_id'] = $media->id;
        }

        $post = BlogPost::create($data);
        $post->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::active()->orderBy('name')->get();
        $tags       = BlogTag::orderBy('name')->get();
        $authors    = \App\Models\Admin::active()->orderBy('name')->get();
        $blog->load(['tags', 'featuredImage']);

        // Rename to $post so the shared form view works correctly
        $post = $blog;

        return view('admin.blog.edit', compact('post', 'categories', 'tags', 'authors'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:300',
            'slug'             => 'nullable|string|max:300|unique:blog_posts,slug,' . $blog->id,
            'blog_category_id' => 'required|exists:blog_categories,id',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'status'           => 'required|in:draft,published,scheduled',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tag_ids'          => 'nullable|array',
            'tag_ids.*'        => 'exists:blog_tags,id',
        ]);

        if ($data['status'] === 'published' && empty($data['published_at']) && ! $blog->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $media                     = $this->mediaService->upload($request->file('featured_image'), 'blog');
            $data['featured_image_id'] = $media->id;
        }

        $blog->update($data);
        $blog->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->tags()->detach();
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Post deleted.');
    }

    public function publish(BlogPost $blog)
    {
        $blog->update(['status' => 'published', 'published_at' => $blog->published_at ?? now()]);
        return response()->json(['success' => true]);
    }

    public function unpublish(BlogPost $blog)
    {
        $blog->update(['status' => 'draft']);
        return response()->json(['success' => true]);
    }
}
