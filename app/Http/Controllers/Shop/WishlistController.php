<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    /**
     * Display a listing of the user's wishlists.
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->withCount('items')->get();
        return view('shop.wishlist.index', compact('wishlists'));
    }

    /**
     * Store a newly created wishlist in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        $wishlist = Auth::user()->wishlists()->create([
            'name' => $validated['name'],
            'is_public' => $request->has('is_public'),
            'is_default' => false, // Default is usually created on registration or first add
        ]);

        return back()->with('success', 'Lista de deseos creada con éxito.');
    }

    /**
     * Display the specified wishlist.
     */
    public function show(Wishlist $wishlist)
    {
        // Authorization
        if ($wishlist->user_id !== Auth::id() && !$wishlist->is_public) {
            abort(403);
        }

        $wishlistItems = $wishlist->items()->with('product')->get();
        return view('shop.wishlist.show', compact('wishlist', 'wishlistItems'));
    }

    /**
     * Update the specified wishlist in storage.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        $wishlist->update([
            'name' => $validated['name'],
            'is_public' => $request->has('is_public'),
        ]);

        return back()->with('success', 'Lista de deseos actualizada con éxito.');
    }

    /**
     * Remove the specified wishlist from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        if ($wishlist->is_default) {
            return back()->with('error', 'No puedes eliminar tu lista de deseos predeterminada.');
        }

        $wishlist->delete();

        return redirect()->route('wishlist.index')->with('success', 'Lista de deseos eliminada con éxito.');
    }

    /**
     * Toggle a product in the default wishlist (AJAX).
     */
    public function toggle(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Por favor, inicie sesión para añadir a la lista de deseos'], 401);
        }

        $user = Auth::user();
        
        // Find or create default wishlist
        $wishlist = $user->wishlists()->where('is_default', true)->first();
        if (!$wishlist) {
            $wishlist = $user->wishlists()->create([
                'name' => 'Mi Lista de Deseos',
                'is_default' => true,
            ]);
        }

        $exists = $wishlist->items()->where('product_id', $product->id)->exists();

        if ($exists) {
            $wishlist->items()->where('product_id', $product->id)->delete();
            return response()->json(['status' => 'removed', 'message' => 'Producto eliminado de la lista de deseos']);
        } else {
            $wishlist->items()->create(['product_id' => $product->id]);
            return response()->json(['status' => 'added', 'message' => 'Producto añadido a la lista de deseos']);
        }
    }
    
    /**
     * Remove an item from a specific wishlist.
     */
    public function removeItem(Wishlist $wishlist, $itemId)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }
        
        // $itemId could be product_id or WishlistItem id. 
        // Let's assume it's the product ID for consistency with UI usually passing product IDs
        // Or cleaner: Use WishlistItem ID if the view has it. 
        // Let's look for the item by product_id within the wishlist for safety.
        
        $wishlist->items()->where('product_id', $itemId)->delete();
        
        return back()->with('success', 'Artículo eliminado de la lista de deseos.');
    }

    /**
     * Get user's wishlists as JSON (for modal).
     */
    public function getWishlistsJson()
    {
        if (!Auth::check()) {
            return response()->json([], 401);
        }
        
        $wishlists = Auth::user()->wishlists()->select('id', 'name', 'is_default')->get();
        return response()->json($wishlists);
    }

    /**
     * Add a product to a specific wishlist (AJAX).
     */
    public function addItem(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = $wishlist->items()->where('product_id', $request->product_id)->exists();

        if ($exists) {
            return response()->json(['status' => 'exists', 'message' => 'El producto ya está en esta lista de deseos.']);
        }

        $wishlist->items()->create(['product_id' => $request->product_id]);

        return response()->json(['status' => 'added', 'message' => 'Producto añadido a ' . $wishlist->name]);
    }
}