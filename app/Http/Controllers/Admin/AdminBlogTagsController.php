<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogTagsController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('posts')->orderBy('name')->paginate(50);
        return view('admin.blog-tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:blog_tags,name',
        ]);

        BlogTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Tag created.');
    }

    public function update(Request $request, BlogTag $blogTag)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:blog_tags,name,' . $blogTag->id,
        ]);

        $blogTag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(BlogTag $blogTag)
    {
        $blogTag->posts()->detach();
        $blogTag->delete();

        return back()->with('success', 'Tag deleted.');
    }
}
