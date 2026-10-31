<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\Product;

class ProductData
{
    public readonly int $productId;
    public readonly string $productName;
    public readonly string $productDescription;
    public readonly int $categoryId;
    public readonly int $productPriceWithTax;
    public readonly int $productTax;
    public readonly int $productPointPrice;
    public readonly int $productReviewScoreAverage;

    public function __construct(Product $product)
    {
        $this->productId = $product->productId->value;
        $this->productName = $product->productName->value;
        $this->productDescription = $product->productDescription->value;
        $this->categoryId = $product->categoryId->value;
        $this->productPriceWithTax = $product->productPriceWithTax->value;
        $this->productTax = $product->productTax->value;
        $this->productPointPrice = $product->productPointPrice->value;
        $this->productReviewScoreAverage = $product->productReviewScoreAverage->value;
    }
}
