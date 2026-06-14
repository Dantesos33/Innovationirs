<?php
// app/Http/Controllers/PartsController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EquipmentModel;
use App\Models\EquipmentType;
use App\Models\Make;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PartsController extends Controller
{
    // ─── Parts Catalog / Listing ────────────────────────────────
    public function index(Request $request)
    {
        $query = Part::active()
            ->with(['make', 'categories', 'images.media']) // images.media needed for public_url
            ->withCount('images');

        // ── Search
        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('part_number', 'like', "%{$s}%")
                    ->orWhere('oem_part_number', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%")
                    ->orWhere('short_description', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // ── Filters
        if ($request->filled('category')) {
            // FIX: categories is many-to-many — use whereHas instead of direct column
            $query->whereHas('categories', fn($q) => $q->where('categories.id', $request->category));
        }
        if ($request->filled('make')) {
            $query->where('make_id', $request->make);
        }
        if ($request->filled('type')) {
            // FIX: column is condition_type, not condition
            $query->whereIn('condition_type', (array) $request->type);
        }
        if ($request->filled('equipment_type')) {
            $query->where('equipment_type_id', $request->equipment_type);
        }
        if ($request->boolean('in_stock')) {
            // FIX: column is stock_status, not stock
            $query->where('stock_status', 'in_stock');
        }

        // ── Sort
        match ($request->sort ?? 'newest') {
            'name_asc' => $query->orderBy('name'),
            'popular'  => $query->orderByDesc('views'),
            default    => $query->orderByDesc('created_at'),
        };

        $parts = $query->paginate(24)->withQueryString();

        // Sidebar data
        $filterMakes = Cache::remember('filter.makes', 3600, fn() =>
            Make::active()->withCount(['parts' => fn($q) => $q->active()])->orderBy('name')->get()
        );
        $filterCategories = Cache::remember('filter.categories', 3600, fn() =>
            Category::active()->withCount(['parts' => fn($q) => $q->active()])->orderBy('name')->get()
        );
        $filterEquipmentTypes = Cache::remember('filter.equipment_types', 3600, fn() =>
            EquipmentType::active()->withCount(['parts' => fn($q) => $q->active()])->orderBy('name')->get()
        );

        return view('parts.index', compact(
            'parts',
            'filterMakes',
            'filterCategories',
            'filterEquipmentTypes'
        ));
    }

    // ─── Part Detail ─────────────────────────────────────────────
    public function show(string $slug)
    {
        $part = Part::active()
            ->where('slug', $slug)
            ->with([
                'make',
                'categories',
                'equipmentType',
                'images' => fn($q) => $q->with('media')->orderBy('sort_order'),
                // FIX: relation is fitsModels, not compatibleModels
                'fitsModels.make',
            ])
            ->firstOrFail();

        // Track view
        $part->increment('views');

        // Related parts — same category, different part
        // FIX: use whereHas for many-to-many categories relationship
        $primaryCategory = $part->primaryCategory();
        $related         = Part::active()
            ->when($primaryCategory, fn($q) =>
                $q->whereHas('categories', fn($c) => $c->where('categories.id', $primaryCategory->id))
            )
            ->where('id', '!=', $part->id)
            ->with(['make', 'categories', 'images.media'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Same make, different category
        $sameMake = Part::active()
            ->where('make_id', $part->make_id)
            ->where('id', '!=', $part->id)
        // FIX: relation is categories (many-to-many), not category
            ->with(['make', 'categories', 'images.media'])
            ->take(4)
            ->get();

        return view('parts.show', compact('part', 'related', 'sameMake'));
    }

    // ─── Filtered by Condition ────────────────────────────────────
    public function newParts(Request $request)
    {
        return $this->filteredByCondition($request, 'new', 'New Heavy Equipment Parts');
    }

    public function usedParts(Request $request)
    {
        return $this->filteredByCondition($request, 'used', 'Used Heavy Equipment Parts');
    }

    public function rebuiltParts(Request $request)
    {
        return $this->filteredByCondition($request, 'rebuilt', 'Rebuilt Heavy Equipment Parts');
    }

    private function filteredByCondition(Request $request, string $condition, string $title)
    {
        $query = Part::active()
        // FIX: column is condition_type, not condition
            ->where('condition_type', $condition)
            ->with(['make', 'categories', 'images.media']);

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('part_number', 'like', "%{$s}%");
            });
        }

        $parts = $query->orderByDesc('created_at')->paginate(24)->withQueryString();

        $filterMakes          = Cache::remember('filter.makes', 3600, fn() => Make::active()->orderBy('name')->get());
        $filterCategories     = Cache::remember('filter.categories', 3600, fn() => Category::active()->orderBy('name')->get());
        $filterEquipmentTypes = Cache::remember('filter.equipment_types', 3600, fn() => EquipmentType::active()->orderBy('name')->get());

        return view('parts.index', compact('parts', 'filterMakes', 'filterCategories', 'filterEquipmentTypes'))
            ->with([
                'pageTitle'       => $title,
                'activeCondition' => $condition,
            ]);
    }

    // ─── Makes Index ──────────────────────────────────────────────
    public function makesIndex()
    {
        $makes = Make::active()
            ->withCount(['parts' => fn($q) => $q->active()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('makes.index', compact('makes'));
    }

    // ─── Make Detail ──────────────────────────────────────────────
    public function makeShow(Request $request, string $slug)
    {
        $make = Make::where('slug', $slug)
            ->where('is_active', true)
            ->withCount(['parts' => fn($q) => $q->active()])
            ->firstOrFail();

        $query = Part::active()
            ->where('make_id', $make->id)
            ->with(['categories', 'images.media']);

        if ($request->filled('category')) {
            // FIX: many-to-many — use whereHas
            $query->whereHas('categories', fn($q) => $q->where('categories.id', $request->category));
        }
        if ($request->filled('type')) {
            // FIX: condition_type not condition
            $query->where('condition_type', $request->type);
        }

        match ($request->sort ?? 'newest') {
            'popular' => $query->orderByDesc('views'),
            default   => $query->orderByDesc('created_at'),
        };

        $parts      = $query->paginate(24)->withQueryString();
        $categories = Category::active()
            ->whereHas('parts', fn($q) => $q->active()->where('make_id', $make->id))
            ->withCount(['parts' => fn($q) => $q->active()->where('make_id', $make->id)])
            ->get();

        // Models for this make
        $models = EquipmentModel::where('make_id', $make->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('makes.show', compact('make', 'parts', 'categories', 'models'));
    }

    // ─── Categories Index ─────────────────────────────────────────
    public function categoriesIndex()
    {
        $categories = Category::active()
            ->withCount(['parts' => fn($q) => $q->active()])
            ->with('image_media')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    // ─── Category Detail ──────────────────────────────────────────
    public function categoryShow(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->withCount(['parts' => fn($q) => $q->active()])
            ->firstOrFail();

        $query = Part::active()
        // FIX: many-to-many — use whereHas instead of direct category_id column
            ->whereHas('categories', fn($q) => $q->where('categories.id', $category->id))
            ->with(['make', 'images.media']);

        if ($request->filled('make')) {
            $query->where('make_id', $request->make);
        }
        if ($request->filled('type')) {
            // FIX: condition_type not condition
            $query->where('condition_type', $request->type);
        }

        match ($request->sort ?? 'newest') {
            'popular' => $query->orderByDesc('views'),
            default   => $query->orderByDesc('created_at'),
        };

        $parts = $query->paginate(24)->withQueryString();
        $makes = Make::active()
        // FIX: pivot lookup for many-to-many
            ->whereHas('parts', fn($q) => $q->active()->whereHas('categories', fn($c) => $c->where('categories.id', $category->id)))
            ->withCount(['parts' => fn($q) => $q->active()->whereHas('categories', fn($c) => $c->where('categories.id', $category->id))])
            ->orderBy('name')
            ->get();

        return view('categories.show', compact('category', 'parts', 'makes'));
    }

    // ─── Equipment Types Index ────────────────────────────────────
    public function equipmentTypesIndex()
    {
        $equipmentTypes = EquipmentType::active()
            ->withCount(['parts' => fn($q) => $q->active()])
            ->with('image_media')
            ->orderBy('sort_order')
            ->get();

        return view('equipment-types.index', compact('equipmentTypes'));
    }

    // ─── Equipment Type Detail ────────────────────────────────────
    public function equipmentTypeShow(Request $request, string $slug)
    {
        $equipmentType = EquipmentType::where('slug', $slug)
            ->where('is_active', true)
            ->withCount(['parts' => fn($q) => $q->active()])
            ->with('image_media')
            ->firstOrFail();

        $query = Part::active()
            ->where('equipment_type_id', $equipmentType->id)
        // FIX: relation is categories (many-to-many), not category
            ->with(['make', 'categories', 'images.media']);

        if ($request->filled('make')) {
            $query->where('make_id', $request->make);
        }
        if ($request->filled('category')) {
            // FIX: use whereHas for many-to-many categories
            $query->whereHas('categories', fn($q) => $q->where('categories.id', $request->category));
        }

        $parts = $query->orderByDesc('created_at')->paginate(24)->withQueryString();

        $makes = Make::active()
            ->whereHas('parts', fn($q) => $q->active()->where('equipment_type_id', $equipmentType->id))
            ->orderBy('name')
            ->get();

        $categories = Category::active()
            ->whereHas('parts', fn($q) => $q->active()->where('equipment_type_id', $equipmentType->id))
            ->orderBy('name')
            ->get();

        // Sidebar navigation list of all equipment types
        $navEquipmentTypes = \Illuminate\Support\Facades\Cache::remember('nav.equipment_types', 3600, fn() =>
            EquipmentType::active()->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'slug'])
        );

        return view('equipment-types.show', compact('equipmentType', 'parts', 'makes', 'categories', 'navEquipmentTypes'));
    }
}
