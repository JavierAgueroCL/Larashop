<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('shop.wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Please login to add to wishlist'], 401);
        }

        $user = Auth::user();
        $exists = $user->wishlist()->where('product_id', $product->id)->exists();

        if ($exists) {
            $user->wishlist()->where('product_id', $product->id)->delete();
            return response()->json(['status' => 'removed', 'message' => 'Product removed from wishlist']);
        } else {
            $user->wishlist()->create(['product_id' => $product->id]);
            return response()->json(['status' => 'added', 'message' => 'Product added to wishlist']);
        }
    }
}
