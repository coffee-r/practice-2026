<?php

namespace App\Packages\Cart\Usecase;

use App\Packages\Cart\Domain\Cart;

class CartData
{
    public readonly int $cartId;
    public readonly int $userId;
    public readonly array $cartProductList;

    public function __construct(Cart $cart)
    {
        $this->cartId = $cart->cartId->value;
        $this->userId = $cart->userId->value;
        $cartProductList = [];
        foreach ($cart->cartProductList->value as $key => $product) {
            $cartProductList[] = new CartProductData($product);
        }
        $this->cartProductList = $cartProductList;
    }
}