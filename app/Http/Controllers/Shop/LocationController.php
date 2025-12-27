<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Comuna;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function regions()
    {
        return response()->json(Region::all());
    }

    public function comunas($regionId)
    {
        $region = Region::findOrFail($regionId);
        return response()->json($region->comunas()->orderBy('comuna')->get());
    }
}
