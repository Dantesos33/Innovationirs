<?php
namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BlogRepositoryInterface
{
    public function published(\Illuminate\Http\Request $request): \Illuminate\Database\Eloquent\Builder;
    public function findBySlugAndId(string $slug, int $id): ?\App\Models\BlogPost;
    public function getRecent(int $limit = 5) : Collection;
}
