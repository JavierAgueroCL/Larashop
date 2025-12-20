<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;
use App\Models\User;

interface CartRepositoryInterface
{
    public function findBySession(string $sessionId): ?Cart;
    public function findByUser(User $user): ?Cart;
    public function create(array $data): Cart;
    public function update(Cart $cart, array $data): bool;
    public function delete(Cart $cart): bool;
}
