<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\SmallCategory;

class SmallCategoryData
{
    public readonly int $categoryId;
    public readonly string $categoryName;
    public readonly int $parentCategoryId;

    public function __construct(SmallCategory $smallCategory)
    {
        $this->categoryId = $smallCategory->categoryId->value;
        $this->categoryName = $smallCategory->categoryName->value;
        $this->parentCategoryId = $smallCategory->parentCategoryId->value;
    }
}