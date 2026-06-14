<?php
namespace App\Repositories;

use App\Models\BlogPost;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BlogRepository implements BlogRepositoryInterface
{
    public function published(Request $request): Builder
    {
        $query = BlogPost::published()
            ->with(['author', 'category', 'featuredImage', 'tags']);

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%");
            });
        }

        return $query->latest('published_at');
    }

    public function findBySlugAndId(string $slug, int $id): ?BlogPost
    {
        return BlogPost::published()
            ->with(['author', 'category', 'tags', 'featuredImage'])
            ->findOrFail($id);
    }

    public function getRecent(int $limit = 5): Collection
    {
        return BlogPost::published()
            ->with('featuredImage')
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
