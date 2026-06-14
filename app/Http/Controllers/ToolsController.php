<?php
// app/Http/Controllers/ToolsController.php

namespace App\Http\Controllers;

use App\Models\HeavyDutyTool;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    // ─── Tools Catalog / Listing ────────────────────────────────────────
    public function index(Request $request)
    {
        $query = HeavyDutyTool::active()
            ->with(['primaryImage', 'images']);

        // ── Search
        if ($s = $request->search) {
            $query->search($s);
        }

        // ── Filters
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->inStock();
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // ── Sort
        match ($request->sort ?? 'newest') {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc'   => $query->orderBy('name'),
            'popular'    => $query->orderByDesc('views'),
            'featured'   => $query->orderByDesc('is_featured')->orderBy('sort_order'),
            default      => $query->orderByDesc('created_at'),
        };

        $tools = $query->paginate(24)->withQueryString();

        // Sidebar filter data
        $brands = HeavyDutyTool::active()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('tools.index', compact('tools', 'brands'));
    }

    // ─── Tool Detail Page ────────────────────────────────────────────────
    public function show(string $slug)
    {
        $tool = HeavyDutyTool::active()
            ->where('slug', $slug)
            ->with(['primaryImage', 'images'])
            ->firstOrFail();

        // Increment view counter (fire-and-forget)
        $tool->increment('views');

        // Build image list for gallery
        $allImages = collect();

        if ($tool->primaryImage) {
            $allImages->push(['url' => $tool->primaryImage->public_url, 'alt' => $tool->name]);
        }

        foreach ($tool->images as $img) {
            if ($img->media && $img->media->public_url !== ($tool->primaryImage?->public_url)) {
                $allImages->push(['url' => $img->media->public_url, 'alt' => $tool->name]);
            }
        }

        $mainImage = $allImages->first()['url'] ?? null;

        // Related tools: same brand or just other active tools
        $relatedTools = HeavyDutyTool::active()
            ->where('id', '!=', $tool->id)
            ->when($tool->brand, fn($q) => $q->where('brand', $tool->brand))
            ->with(['primaryImage', 'images'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        // If not enough related by brand, pad with random others
        if ($relatedTools->count() < 4) {
            $existingIds  = $relatedTools->pluck('id')->push($tool->id)->toArray();
            $relatedTools = $relatedTools->merge(
                HeavyDutyTool::active()
                    ->whereNotIn('id', $existingIds)
                    ->with(['primaryImage', 'images'])
                    ->inRandomOrder()
                    ->take(4 - $relatedTools->count())
                    ->get()
            );
        }

        return view('tools.show', compact('tool', 'allImages', 'mainImage', 'relatedTools'));
    }
}
