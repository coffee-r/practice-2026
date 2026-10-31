<?php

namespace App\Packages\Cart\Domain;

interface CartRepositoryInterface
{
    public function find(CartId $id) : Cart | null;
    public function save(Cart $cart);
}