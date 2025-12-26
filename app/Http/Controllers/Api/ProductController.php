<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::with(['categories', 'brand', 'images']);

        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $products = $query->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): ProductResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'slug' => 'required|string|max:255|unique:products,slug',
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_quantity' => 'integer|min:0',
            'is_active' => 'boolean',
            'category_ids' => 'array', // Array of category IDs
            'category_ids.*' => 'exists:categories,id',
        ]);

        $product = Product::create($validated);

        if (!empty($validated['category_ids'])) {
            $product->categories()->sync($validated['category_ids']);
        }

        return new ProductResource($product->load('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): ProductResource
    {
        $product = Product::with(['categories', 'brand', 'images'])->findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): ProductResource
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'sometimes|required|string|max:100|unique:products,sku,' . $id,
            'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $id,
            'base_price' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_quantity' => 'integer|min:0',
            'is_active' => 'boolean',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $product->update($validated);

        if (isset($validated['category_ids'])) {
            $product->categories()->sync($validated['category_ids']);
        }

        return new ProductResource($product->load('categories'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->noContent();
    }
}