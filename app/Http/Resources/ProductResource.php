<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand' => $this->brand ? $this->brand->name : null,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'formatted_price' => $this->base_price_formatted,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images' => $this->images->pluck('image_path'), // Simple array of images
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}