<?php

namespace App\Packages\Cart\Domain;

interface CartProductFactoryInterface
{
    public function create(ProductId $productId, ProductQuantity $productQuantity) : CartProduct;
}