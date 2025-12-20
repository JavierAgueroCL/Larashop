<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(protected Cart $model)
    {
    }

    public function findBySession(string $sessionId): ?Cart
    {
        return $this->model->with(['items.product.images', 'items.combination'])
                           ->where('session_id', $sessionId)
                           ->where('status', 'active')
                           ->first();
    }

    public function findByUser(User $user): ?Cart
    {
        return $this->model->with(['items.product.images', 'items.combination'])
                           ->where('user_id', $user->id)
                           ->where('status', 'active')
                           ->first();
    }

    public function create(array $data): Cart
    {
        return $this->model->create($data);
    }

    public function update(Cart $cart, array $data): bool
    {
        return $cart->update($data);
    }

    public function delete(Cart $cart): bool
    {
        return $cart->delete();
    }
}
