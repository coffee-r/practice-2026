<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\LargeCategoryList;

class LargeCategoryListData
{
    public readonly array $value;

    public function __construct(LargeCategoryList $largeCategoryList)
    {
        $value = [];
        foreach ($largeCategoryList->value as $key => $largeCategory) {
            $value[] = new LargeCategoryData($largeCategory);
        }
        $this->value = $value;
    }
}