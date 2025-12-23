<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $table = 'regiones';
    public $timestamps = false;

    protected $fillable = ['region', 'abreviatura', 'capital'];

    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class);
    }

    public function comunas()
    {
        return $this->hasManyThrough(Comuna::class, Provincia::class);
    }
}
