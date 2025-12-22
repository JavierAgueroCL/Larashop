<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model)
    {
    }

    public function find(int $id): ?Product
    {
        return $this->model->with(['brand', 'images', 'categories'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with(['brand', 'images', 'categories', 'combinations'])
                           ->where('slug', $slug)
                           ->first();
    }

    public function all(): Collection
    {
        return $this->model->with(['brand', 'images'])->get();
    }

    public function paginate(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->paginate($perPage);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);
        return $product ? $product->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product ? $product->delete() : false;
    }

    public function getFeatured(int $limit = 10): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->inStock()
                           ->featured()
                           ->limit($limit)
                           ->get();
    }

    public function getNew(int $limit = 10): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->inStock()
                           ->latest()
                           ->limit($limit)
                           ->get();
    }

    public function getBestSellers(int $limit = 10): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->inStock()
                           ->orderBy('sales_count', 'desc')
                           ->limit($limit)
                           ->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->inStock()
                           ->whereFullText(['name', 'short_description', 'description'], $query)
                           ->get();
    }

    public function filterByCategory(int $categoryId): Collection
    {
        return $this->model->with(['brand', 'images'])
                           ->active()
                           ->inStock()
                           ->whereHas('categories', function ($q) use ($categoryId) {
                               $q->where('categories.id', $categoryId);
                           })
                           ->get();
    }

    public function getFiltered(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->model->with(['brand', 'images'])->active()->inStock();

        if (!empty($filters['search'])) {
             // Fallback to LIKE if FullText is not set up or for partial matches
             $query->where(function($q) use ($filters) {
                 $q->where('name', 'like', '%' . $filters['search'] . '%')
                   ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                   ->orWhere('short_description', 'like', '%' . $filters['search'] . '%');
             });
        }

        if (!empty($filters['category'])) {
             $query->whereHas('categories', function ($q) use ($filters) {
                 $q->where('categories.slug', $filters['category']);
             });
        }

        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('base_price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
             $query->latest();
        }

        return $query->paginate($perPage);
    }
}
