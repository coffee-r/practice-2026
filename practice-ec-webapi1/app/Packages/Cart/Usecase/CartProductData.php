<?php

namespace App\Packages\Cart\Usecase;

use App\Packages\Cart\Domain\CartProduct;

class CartProductData
{
    public readonly int $productId;
    public readonly string $productName;
    public readonly int $productPriceWithTax;
    public readonly int $productTax;
    public readonly int $productPointPrice;
    public readonly int $productQuantity;

    public function __construct(CartProduct $cartProduct)
    {
        $this->productId = $cartProduct->productId->value;
        $this->productName = $cartProduct->productName->value;
        $this->productPriceWithTax = $cartProduct->productPriceWithTax->value;
        $this->productTax = $cartProduct->productTax->value;
        $this->productPointPrice = $cartProduct->productPointPrice->value;
        $this->productQuantity = $cartProduct->productQuantity->value;        
    }
}