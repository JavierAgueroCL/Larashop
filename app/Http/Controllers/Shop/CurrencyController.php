<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    public function switch($code)
    {
        if (!in_array($code, ['CLP', 'USD'])) {
            abort(400);
        }

        Session::put('currency', $code);
        return back();
    }
}
