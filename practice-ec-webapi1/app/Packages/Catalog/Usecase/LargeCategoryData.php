<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\LargeCategory;

class LargeCategoryData
{
    public readonly int $categoryId;
    public readonly string $categoryName;
    public readonly array $mediumCategoryList;

    public function __construct(LargeCategory $largeCategory)
    {
        $this->categoryId = $largeCategory->categoryId->value;
        $this->categoryName = $largeCategory->categoryName->value;

        $mediumCategoryList = [];
        foreach ($largeCategory->mediumCategoryList as $key => $mediumCategory) {
            $mediumCategoryList[] = new MediumCategoryData($mediumCategory);
        }
        $this->mediumCategoryList = $mediumCategoryList;
    }
}