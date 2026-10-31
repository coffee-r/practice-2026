<?php

namespace App\Packages\Catalog\Domain;

class Product
{
    public readonly ProductId $productId;
    public ProductName $productName;
    public ProductDescription $productDescription;
    public CategoryId $categoryId;
    public ProductUnitPriceWithTax $productPriceWithTax;
    public ProductUnitTax $productTax;
    public ProductUnitPointPrice $productPointPrice;
    public ProductReviewScoreAverage $productReviewScoreAverage;

    public function __construct(
        ProductId $productId,
        ProductName $productName,
        ProductDescription $productDescription,
        CategoryId $categoryId,
        ProductUnitPriceWithTax $productPriceWithTax,
        ProductUnitTax $productTax,
        ProductUnitPointPrice $productPointPrice,
        ProductReviewScoreAverage $productReviewScoreAverage
    ){
            $this->productId = $productId;
            $this->productName = $productName;
            $this->productDescription = $productDescription;
            $this->categoryId = $categoryId;
            $this->productPriceWithTax = $productPriceWithTax;
            $this->productTax = $productTax;
            $this->productPointPrice = $productPointPrice;
            $this->productReviewScoreAverage = $productReviewScoreAverage;
    }
}
