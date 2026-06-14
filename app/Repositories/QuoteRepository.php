<?php
namespace App\Repositories;

use App\Models\QuoteRequest;
use App\Repositories\Contracts\QuoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuoteRepository implements QuoteRepositoryInterface
{
    public function create(array $data): QuoteRequest
    {
        return QuoteRequest::create($data);
    }

    public function findById(int $id): ?QuoteRequest
    {
        return QuoteRequest::find($id);
    }

    public function updateStatus(int $id, string $status): void
    {
        QuoteRequest::where('id', $id)->update(['status' => $status]);
    }

    public function getByStatus(string $status): Collection
    {
        return QuoteRequest::where('status', $status)
            ->latest()
            ->get();
    }

    public function getStatusCounts(): array
    {
        return QuoteRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}
