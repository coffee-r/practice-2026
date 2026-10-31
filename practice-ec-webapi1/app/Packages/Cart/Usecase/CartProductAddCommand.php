<?php

namespace App\Packages\Cart\Usecase;

class CartProductAddCommand
{
    public readonly int $cartId;
    public readonly int $productId;
    public readonly int $productQuantity;

    public function __construct(int $cartId, int $productId, int $productQuantity)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->productQuantity = $productQuantity;
    }
}