<?php
// ═══════════════════════════════════════════════════════════
// app/Http/Controllers/SearchController.php
// ═══════════════════════════════════════════════════════════

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Make;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', $request->input('search', '')));

        // Sidebar data — always needed for the search page layout
        $navCategories = Cache::remember('search.nav_categories', 3600, fn() =>
            Category::active()->orderBy('name')->take(12)->get(['id', 'name', 'slug'])
        );
        $navMakes = Cache::remember('search.nav_makes', 3600, fn() =>
            Make::active()->orderBy('sort_order')->orderBy('name')->take(16)->get(['id', 'name', 'slug'])
        );

        if (strlen($q) < 2) {
            return view('pages.search', [
                'q'             => $q,
                'parts'         => collect(),
                'posts'         => collect(),
                'total'         => 0,
                'navCategories' => $navCategories,
                'navMakes'      => $navMakes,
            ]);
        }

        // Parts search — use categories (many-to-many) not category
        $parts = Part::active()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('part_number', 'like', "%{$q}%")
                    ->orWhere('oem_part_number', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->with(['make', 'categories', 'images'])
            ->orderByDesc('views')
            ->paginate(20, ['*'], 'parts_page')
            ->withQueryString();

        // Blog posts search
        $posts = BlogPost::published()
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            })
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $total = $parts->total() + $posts->count();

        return view('pages.search', compact('q', 'parts', 'posts', 'total', 'navCategories', 'navMakes'));
    }
}
