<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function all(): Collection;
    public function paginate(int $perPage = 12): LengthAwarePaginator;
    public function create(array $data): Product;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getFeatured(int $limit = 10): Collection;
    public function getNew(int $limit = 10): Collection;
    public function getBestSellers(int $limit = 10): Collection;
    public function search(string $query): Collection;
    public function filterByCategory(int $categoryId): Collection;
    public function getFiltered(array $filters, int $perPage = 12): LengthAwarePaginator;
}
