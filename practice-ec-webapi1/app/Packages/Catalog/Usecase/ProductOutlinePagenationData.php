<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\Product;
use App\Packages\Shared\Usecase\PagenationData;

class ProductOutlinePagenationData
{
    public readonly array $productOutlineList;
    public readonly PagenationData $pagenationData;

    public function __construct(array $productOutlineList, PagenationData $pagenationData)
    {
        $this->productOutlineList = $productOutlineList;
        $this->pagenationData = $pagenationData;
    }
}
