<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarkenCity extends Model
{
    protected $table = 'starken_cities';
    
    protected $fillable = [
        'region_code',
        'city_code',
        'city_name',
        'comuna_code',
        'comuna_name',
    ];
}
