<?php
namespace App\Services;

use App\Models\Part;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PartFilterService
{
    /**
     * Build a filtered, eager-loaded parts query.
     *
     * @param  Request  $request      Incoming HTTP request for query params
     * @param  array    $defaults     Pre-applied filters (make_id, category_id, etc.)
     */
    public function apply(Request $request, array $defaults = []): Builder
    {
        $query = Part::with(['primaryImage', 'make', 'categories'])
            ->where('status', 'active');

        // ── Pre-applied defaults (from route context) ──────────────────
        foreach ($defaults as $key => $value) {
            match ($key) {
                'make_id'           => $query->where('make_id', $value),
                'equipment_type_id' => $query->where('equipment_type_id', $value),
                'condition_type'    => $query->where('condition_type', $value),
                'category_id'       => $query->whereHas('categories', function ($q) use ($value) {
                    $q->where('categories.id', $value);
                }),
                default             => null,
            };
        }

        // ── Request-driven filters ─────────────────────────────────────

        if ($request->filled('make')) {
            $query->whereHas('make', fn($q) =>
                $q->where('slug', $request->make)
            );
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', fn($q) =>
                $q->where('slug', $request->category)
            );
        }

        if ($request->filled('equipment')) {
            $query->whereHas('equipmentType', fn($q) =>
                $q->where('slug', $request->equipment)
            );
        }

        if ($request->filled('type')) {
            $allowed = ['new', 'used', 'rebuilt', 'salvage'];
            if (in_array($request->type, $allowed)) {
                $query->where('condition_type', $request->type);
            }
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('part_number', 'like', "%{$term}%")
                    ->orWhere('oem_part_number', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%");
            });
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock_status', 'in_stock');
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // ── Sorting ────────────────────────────────────────────────────

        $sort = $request->get('sort', 'newest');

        match ($sort) {
            'name_asc'  => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'popular'   => $query->orderBy('views', 'desc'),
            default     => $query->orderBy('created_at', 'desc'),
        };

        return $query;
    }
}
