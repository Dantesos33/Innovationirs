<?php
namespace App\Repositories;

use App\Models\Part;
use App\Repositories\Contracts\PartRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PartRepository implements PartRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Part::active()
            ->with(['primaryImage', 'make', 'categories'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Part
    {
        return Part::active()->find($id);
    }

    public function findBySlugAndId(string $slug, int $id): ?Part
    {
        return Part::active()
            ->with(['make', 'categories', 'primaryImage', 'images.media', 'fitsModels.make'])
            ->where('slug', $slug)
            ->findOrFail($id);
    }

    public function getByMake(int $makeId): Collection
    {
        return Part::active()
            ->where('make_id', $makeId)
            ->with('primaryImage')
            ->latest()
            ->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return Part::active()
            ->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId))
            ->with('primaryImage')
            ->latest()
            ->get();
    }

    public function getByEquipmentType(int $typeId): Collection
    {
        return Part::active()
            ->where('equipment_type_id', $typeId)
            ->with('primaryImage')
            ->latest()
            ->get();
    }

    public function getFeatured(int $limit = 8): Collection
    {
        return Part::active()
            ->featured()
            ->with(['primaryImage', 'make'])
            ->take($limit)
            ->get();
    }

    public function incrementViews(int $id): void
    {
        Part::where('id', $id)->increment('views');
    }
}
