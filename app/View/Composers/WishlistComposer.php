<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WishlistComposer
{
    public function compose(View $view): void
    {
        $wishlistCount = 0;

        if (Auth::check()) {
            $wishlistCount = Auth::user()->wishlistItems()->count();
        }

        $view->with('wishlistCount', $wishlistCount);
    }
}
