<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comuna extends Model
{
    protected $table = 'comunas';
    public $timestamps = false;

    protected $fillable = ['comuna', 'provincia_id'];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }
}
