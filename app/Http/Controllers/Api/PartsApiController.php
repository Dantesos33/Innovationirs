<?php
// ═══════════════════════════════════════════════════════════
// app/Http/Controllers/Api/PartsApiController.php
// ═══════════════════════════════════════════════════════════

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\EquipmentModel;
use App\Models\Make;
use App\Models\Part;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PartsApiController extends Controller
{
    /**
     * Search suggestions for the header autocomplete.
     * GET /api/parts/search-suggestions?q=...
     */
    public function searchSuggestions(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // Parts by name / part number
        $parts = Part::active()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('part_number', 'like', "{$q}%") // prefix match for part#
                    ->orWhere('oem_part_number', 'like', "{$q}%");
            })
            ->with('make')
            ->orderByDesc('views')
            ->take(6)
            ->get(['id', 'name', 'slug', 'part_number', 'make_id']);

        // Makes
        $makes = Make::active()
            ->where('name', 'like', "%{$q}%")
            ->take(3)
            ->get(['id', 'name', 'slug']);

        // Categories
        $cats = Category::active()
            ->where('name', 'like', "%{$q}%")
            ->take(2)
            ->get(['id', 'name', 'slug']);

        $suggestions = collect();

        foreach ($parts as $part) {
            $suggestions->push([
                'type'  => 'part',
                'label' => $part->name,
                'meta'  => $part->make?->name . ($part->part_number ? ' — #' . $part->part_number : ''),
                'url'   => route('parts.show', $part->slug),
            ]);
        }

        foreach ($makes as $make) {
            $suggestions->push([
                'type'  => 'make',
                'label' => $make->name . ' Parts',
                'meta'  => 'Browse by brand',
                'url'   => route('makes.show', $make->slug),
            ]);
        }

        foreach ($cats as $cat) {
            $suggestions->push([
                'type'  => 'category',
                'label' => $cat->name,
                'meta'  => 'Part category',
                'url'   => route('categories.show', $cat->slug),
            ]);
        }

        return response()->json(['suggestions' => $suggestions->take(10)->values()]);
    }

    /**
     * Single part lookup by part number (for quote form).
     * GET /api/parts/lookup?q=...
     */
    public function lookup(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['part' => null]);
        }

        $part = Part::active()
            ->where(function ($query) use ($q) {
                $query->where('part_number', $q)
                    ->orWhere('oem_part_number', $q)
                    ->orWhere('sku', $q);
            })
            ->with('make')
            ->first(['id', 'name', 'slug', 'part_number', 'make_id', 'short_description', 'condition_type']);

        if (! $part) {
            return response()->json(['part' => null]);
        }

        return response()->json([
            'part' => [
                'name'              => $part->name,
                'part_number'       => $part->part_number,
                'short_description' => $part->short_description, // for autofilling part_description
                'condition_type'    => $part->condition_type,    // new/used/rebuilt
                'make'              => $part->make?->name,
                'make_id'           => $part->make_id,
                'url'               => route('parts.show', $part->slug),
            ],
        ]);
    }

    /**
     * Load equipment models for a given make (for quote form cascade).
     * GET /api/makes/{makeId}/models
     */
    public function modelsByMake(int $makeId): JsonResponse
    {
        $cacheKey = "api.models.make.{$makeId}";

        $models = Cache::remember($cacheKey, 1800, fn() =>
            EquipmentModel::where('make_id', $makeId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'year_start', 'year_end'])
                ->map(fn($m) => [
                    'id'         => $m->id,
                    'name'       => $m->name,
                    'year_range' => $m->year_range,
                ])
        );

        return response()->json(['models' => $models]);
    }

    /**
     * Load models by make SLUG — more reliable than ID since slugs never change
     * even if the DB auto-increment id differs between environments.
     * GET /api/makes/slug/{slug}/models
     */
    public function modelsByMakeSlug(string $slug): JsonResponse
    {
        $make = Make::where('slug', $slug)->where('is_active', true)->first();

        if (! $make) {
            return response()->json(['models' => [], 'make_id' => null]);
        }

        $cacheKey = "api.models.make.{$make->id}";

        $models = Cache::remember($cacheKey, 1800, fn() =>
            EquipmentModel::where('make_id', $make->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'year_start', 'year_end'])
                ->map(fn($m) => [
                    'id'         => $m->id,
                    'name'       => $m->name,
                    'year_range' => $m->year_range,
                ])
        );

        return response()->json([
            'models'  => $models,
            'make_id' => $make->id, // return the real DB id so the select can be updated
        ]);
    }

    /**
     * Featured / latest parts for homepage widgets (optional AJAX refresh).
     * GET /api/parts/featured
     */
    public function featured(): JsonResponse
    {
        $parts = Cache::remember('api.parts.featured', 1800, fn() =>
            Part::active()
                ->where('is_featured', true)                   // FIX: is_featured not featured
                ->with(['make', 'categories', 'images.media']) // FIX: categories (M2M), images.media
                ->orderByDesc('created_at')
                ->take(8)
                ->get()
                ->map(fn($p) => [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'slug'        => $p->slug,
                    'part_number' => $p->part_number,
                    'condition'   => $p->condition_type, // FIX: condition_type not condition
                    'image'       => $p->images->first()?->public_url,
                    'make'        => $p->make?->name,
                    'category'    => $p->category?->name, // uses getCategoryAttribute accessor
                    'url'         => route('parts.show', $p->slug),
                ])
        );

        return response()->json(['parts' => $parts]);
    }

    /**
     * Parts count by condition (new/used/rebuilt) for homepage badges.
     * GET /api/parts/counts
     */
    public function counts(): JsonResponse
    {
        $counts = Cache::remember('api.parts.counts', 3600, fn() => [
            'new'     => Part::active()->where('condition_type', 'new')->count(),     // FIX: condition_type
            'used'    => Part::active()->where('condition_type', 'used')->count(),    // FIX: condition_type
            'rebuilt' => Part::active()->where('condition_type', 'rebuilt')->count(), // FIX: condition_type
            'total'   => Part::active()->count(),
        ]);

        return response()->json($counts);
    }
}
