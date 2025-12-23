<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comuna;
use App\Models\Region;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function regions()
    {
        return response()->json(Region::all());
    }

    public function comunas(Region $region)
    {
        return response()->json($region->comunas);
    }
}
