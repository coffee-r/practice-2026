<?php

namespace App\Packages\Cart\Infrastructure;

use App\Models\Cart as ModelsCart;
use App\Packages\Cart\Domain\Cart;
use App\Packages\Cart\Domain\CartFactoryInterface;
use App\Packages\Cart\Domain\CartId;
use App\Packages\Cart\Domain\CartProductList;
use App\Packages\Cart\Domain\UserId;

class CartFactory implements CartFactoryInterface
{
    public function create(UserId $userId, CartProductList $cartProductList) : Cart
    {
        $maxCartId = ModelsCart::max('id');
        $nextCartId = new CartId($maxCartId + 1);

        return new Cart($nextCartId, $userId, $cartProductList);
    }
}