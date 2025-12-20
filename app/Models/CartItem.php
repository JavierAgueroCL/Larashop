<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_combination_id',
        'quantity',
        'price_snapshot',
    ];

    protected $with = ['product'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function combination(): BelongsTo
    {
        return $this->belongsTo(ProductCombination::class, 'product_combination_id');
    }

    public function getUnitPriceAttribute()
    {
        // If we have a price snapshot, use it? Or always use current price?
        // Usually, cart uses current price until checkout.
        // But for this simple implementation, let's use product base price.
        // If combination exists, add price impact.
        
        $price = $this->product->base_price;
        
        if ($this->combination) {
            $price += $this->combination->price_impact;
        }

        return $price;
    }

    public function getTotalAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
