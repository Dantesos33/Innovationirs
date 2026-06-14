<?php
namespace App\Repositories\Contracts;

interface QuoteRepositoryInterface
{
    public function create(array $data): \App\Models\QuoteRequest;
    public function findById(int $id): ?\App\Models\QuoteRequest;
    public function updateStatus(int $id, string $status) : void;
    public function getByStatus(string $status): \Illuminate\Database\Eloquent\Collection;
    public function getStatusCounts(): array;
}
