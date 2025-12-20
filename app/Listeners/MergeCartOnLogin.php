<?php

namespace App\Listeners;

use App\Services\Cart\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class MergeCartOnLogin
{
    public function __construct(protected CartService $cartService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $this->cartService->mergeGuestCartToUser(Session::getId(), $event->user);
    }
}
