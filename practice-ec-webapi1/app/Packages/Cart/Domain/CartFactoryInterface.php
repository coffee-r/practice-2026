<?php

namespace App\Packages\Cart\Domain;

interface CartFactoryInterface
{
    public function create(UserId $userId, CartProductList $cartProductList) : Cart;
}