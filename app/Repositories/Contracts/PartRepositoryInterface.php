<?php
namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PartRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function findById(int $id): ?\App\Models\Part;
    public function findBySlugAndId(string $slug, int $id): ?\App\Models\Part;
    public function getByMake(int $makeId) : Collection;
    public function getByCategory(int $categoryId): Collection;
    public function getByEquipmentType(int $typeId): Collection;
    public function getFeatured(int $limit = 8): Collection;
    public function incrementViews(int $id): void;
}
