<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a unique slug for a model, appending a counter if needed.
     */
    public function generate(
        string $value,
        string $model,
        string $column = 'slug',
        ?int $excludeId = null
    ): string {
        $base  = Str::slug($value);
        $slug  = $base;
        $count = 1;

        while ($this->slugExists($model, $column, $slug, $excludeId)) {
            $slug = $base . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function slugExists(
        string $model,
        string $column,
        string $slug,
        ?int $excludeId
    ): bool {
        $query = $model::where($column, $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
