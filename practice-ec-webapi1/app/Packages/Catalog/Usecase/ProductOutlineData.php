<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\Product;

class ProductOutlineData
{
    public readonly int $productId;
    public readonly string $productName;
    public readonly int $categoryId;
    public readonly int $productPriceWithTax;
    public readonly int $productPointPrice;
    public readonly int $productReviewScoreAverage;

    public function __construct(
        int $productId,
        string $productName,
        int $categoryId,
        int $productPriceWithTax,
        int $productPointPrice,
        int $productReviewScoreAverage
    )
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->categoryId = $categoryId;
        $this->productPriceWithTax = $productPriceWithTax;
        $this->productPointPrice = $productPointPrice;
        $this->productReviewScoreAverage = $productReviewScoreAverage;
    }
}
