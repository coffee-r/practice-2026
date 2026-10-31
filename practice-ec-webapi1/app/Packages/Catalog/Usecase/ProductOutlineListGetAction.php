<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\LargeCategoryListRepositoryInterface;
use App\Packages\Catalog\Domain\ProductId;
use App\Packages\Catalog\Domain\ProductOutlineRepositoryInterface;
use App\Packages\Catalog\Domain\ProductRepositoryInterface;
use App\Packages\Shared\Usecase\UsecaseException;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductOutlineListGetAction{

    private readonly ProductOutlineQueryServiceInterface $productOutlineQueryService;

    public function __construct(ProductOutlineQueryServiceInterface $productOutlineQueryService)
    {
        $this->productOutlineQueryService = $productOutlineQueryService;
    }

    public function __invoke(ProductOutlineQuery $productOutlineQuery) : ProductOutlinePagenationData
    {
        $productOutlinePaginationData = $this->productOutlineQueryService->findPagenator($productOutlineQuery);

        if (empty($productOutlinePaginationData)) {
            throw new UsecaseException('条件に合う商品は見つかりませんでした', 404);
        }

        return $productOutlinePaginationData;
    }
}
