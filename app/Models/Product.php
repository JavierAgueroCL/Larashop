<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'sku',
        'name',
        'slug',
        'short_description',
        'description',
        'is_digital',
        'is_active',
        'is_featured',
        'base_price',
        'cost_price',
        'tax_id',
        'weight',
        'width',
        'height',
        'depth',
        'stock_quantity',
        'low_stock_threshold',
        'has_combinations',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_digital' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_combinations' => 'boolean',
        'base_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'depth' => 'decimal:2',
    ];

    // Relaciones
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->withPivot('is_primary');
    }

    public function combinations(): HasMany
    {
        return $this->hasMany(ProductCombination::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Accessors
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first()?->image_path
            ?? $this->images()->first()?->image_path
            ?? '/images/placeholder.jpg';
    }

    public function getBasePriceFormattedAttribute()
    {
        return number_format($this->base_price, 2, ',', '.') . ' €';
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->low_stock_threshold && $this->stock_quantity > 0;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->stock_quantity <= 0;
    }
}
