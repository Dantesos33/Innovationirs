<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\EquipmentType;
use App\Models\HeavyDutyTool;
use App\Models\Make;
use App\Models\Part;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache expensive queries for 30 minutes
        $featured = Cache::remember('home.featured_parts', 1800, function () {
            return Part::active()
                ->where('is_featured', true)
                ->with(['make', 'categories', 'images'])
                ->orderByDesc('created_at')
                ->take(8)
                ->get();
        });

        $testimonials = Cache::remember('home.testimonials', 3600, function () {
            return Testimonial::active()
                ->where('is_featured', true)
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        });

        $recentPosts = Cache::remember('home.recent_posts', 1800, function () {
            return BlogPost::published()
                ->with('author')
                ->orderByDesc('published_at')
                ->take(3)
                ->get();
        });

        $makes = Cache::remember('home.makes', 3600, function () {
            return Make::active()
                ->withCount('parts')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });

        $equipmentTypes = EquipmentType::withCount('parts')
            ->with('image_media')
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $categories = Cache::remember('home.categories', 3600, function () {
            return Category::active()
                ->where('show_on_homepage', true)
                ->withCount('parts')
                ->with('image_media')
                ->orderBy('sort_order')
                ->get();
        });

        $stats = Cache::remember('home.stats', 3600, function () {
            return [
                'total_parts'      => Part::active()->count(),
                'total_makes'      => Make::active()->count(),
                'years_experience' => config('amsparts.years_experience', 20),
                'fleets_served'    => config('amsparts.fleets_served', '50,000+'),
            ];
        });

        // ── Heavy Duty Tools: featured + in-stock, up to 6 on homepage
        $featuredTools = Cache::remember('home.featured_tools', 1800, function () {
            $tools = HeavyDutyTool::active()
                ->featured()
                ->with(['primaryImage', 'images'])
                ->ordered()
                ->take(6)
                ->get();

            // If fewer than 3 featured, pad with newest active tools
            if ($tools->count() < 3) {
                $existingIds = $tools->pluck('id')->toArray();
                $extra       = HeavyDutyTool::active()
                    ->whereNotIn('id', $existingIds)
                    ->with(['primaryImage', 'images'])
                    ->orderByDesc('created_at')
                    ->take(6 - $tools->count())
                    ->get();
                $tools = $tools->merge($extra);
            }

            return $tools;
        });

        return view('pages.home', compact(
            'featured',
            'testimonials',
            'recentPosts',
            'makes',
            'equipmentTypes',
            'categories',
            'stats',
            'featuredTools'
        ));
    }
}
