<?php

namespace App\Packages\Catalog\Usecase;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductOutlineQueryServiceInterface
{
    public function findPagenator(ProductOutlineQuery $productOutlineQuery) : ProductOutlinePagenationData | null;
}