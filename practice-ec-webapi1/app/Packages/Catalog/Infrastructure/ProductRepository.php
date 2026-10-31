<?php

namespace App\Packages\Catalog\Infrastructure;

use App\Models\Product as ModelProduct;
use App\Packages\Catalog\Domain\CategoryId;
use App\Packages\Catalog\Domain\Product;
use App\Packages\Catalog\Domain\ProductDescription;
use App\Packages\Catalog\Domain\ProductId;
use App\Packages\Catalog\Domain\ProductName;
use App\Packages\Catalog\Domain\ProductRepositoryInterface;
use App\Packages\Catalog\Domain\ProductReviewScoreAverage;
use App\Packages\Catalog\Domain\ProductUnitPointPrice;
use App\Packages\Catalog\Domain\ProductUnitPriceWithTax;
use App\Packages\Catalog\Domain\ProductUnitTax;

class ProductRepository implements ProductRepositoryInterface
{
    public function find(ProductId $productId) : Product | null
    {
        $modelProduct = ModelProduct::firstWhere('id', $productId->value);

        if ($modelProduct == null){
            return null;
        }

        return new Product(
            new ProductId($modelProduct->id),
            new ProductName($modelProduct->name),
            new ProductDescription($modelProduct->description),
            new CategoryId($modelProduct->category_id),
            new ProductUnitPriceWithTax($modelProduct->price_with_tax),
            new ProductUnitTax($modelProduct->tax),
            new ProductUnitPointPrice($modelProduct->point_price),
            new ProductReviewScoreAverage($modelProduct->review_score_average)
        );
    }
}