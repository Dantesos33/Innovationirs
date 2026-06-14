<?php
namespace App\Services;

use App\Models\Part;
use Illuminate\Database\Eloquent\Builder;

class PartSearchService
{
    /**
     * Full-text + LIKE fallback search across parts.
     */
    public function search(string $term): Builder
    {
        $term = trim($term);

        return Part::with(['primaryImage', 'make', 'categories'])
            ->where('status', 'active')
            ->where(function (Builder $q) use ($term) {
                // Exact part number match gets highest implicit priority
                $q->where('part_number', $term)
                    ->orWhere('oem_part_number', $term)
                    ->orWhere('sku', $term)
                    ->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('part_number', 'like', "%{$term}%")
                    ->orWhere('oem_part_number', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%")
                    ->orWhere('compatibility_notes', 'like', "%{$term}%");
            })
            ->orderByRaw("
                CASE
                    WHEN part_number = ? THEN 1
                    WHEN oem_part_number = ? THEN 2
                    WHEN sku = ? THEN 3
                    WHEN name LIKE ? THEN 4
                    ELSE 5
                END
            ", [$term, $term, $term, "%{$term}%"]);
    }
}
