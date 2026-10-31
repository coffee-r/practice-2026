<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\LargeCategoryListRepositoryInterface;
use App\Packages\Catalog\Domain\ProductId;
use App\Packages\Catalog\Domain\ProductRepositoryInterface;
use App\Packages\Shared\Usecase\UsecaseException;

class ProductGetAction{

    private readonly ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $productId)
    {
        $product = $this->productRepository->find(new ProductId($productId));

        if ($product == null) {
            throw new UsecaseException('商品ID'.$productId.'の商品は見つかりませんでした');
        }

        return new ProductData($product);
    }
}
