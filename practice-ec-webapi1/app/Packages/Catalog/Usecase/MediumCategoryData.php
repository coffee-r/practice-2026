<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\MediumCategory;

class MediumCategoryData
{
    public readonly int $categoryId;
    public readonly string $categoryName;
    public readonly int $parentCategoryId;
    public readonly array $smallCategoryList;

    public function __construct(MediumCategory $mediumCategory)
    {
        $this->categoryId = $mediumCategory->categoryId->value;
        $this->categoryName = $mediumCategory->categoryName->value;
        $this->parentCategoryId = $mediumCategory->parentCategoryId->value;

        $smallCategoryList = [];
        foreach ($mediumCategory->smallCategoryList as $key => $smallCategory) {
            $smallCategoryList[] = new SmallCategoryData($smallCategory);
        }
        $this->smallCategoryList = $smallCategoryList;
    }
}