<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_section',
        'attribute_key',
        'attribute_name',
        'attribute_value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}