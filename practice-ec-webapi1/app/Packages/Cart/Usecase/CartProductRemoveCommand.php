<?php

namespace App\Packages\Cart\Usecase;

class CartProductRemoveCommand
{
    public readonly int $cartId;
    public readonly int $productId;

    public function __construct(int $cartId, int $productId)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
    }
}