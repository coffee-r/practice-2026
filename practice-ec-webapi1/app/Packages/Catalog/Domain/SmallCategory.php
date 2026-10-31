<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class SmallCategory
{
    public readonly CategoryId $categoryId;
    public readonly CategoryName $categoryName;
    public readonly CategoryId $parentCategoryId;

    public function __construct(CategoryId $categoryId, CategoryName $categoryName, CategoryId $parentCategoryId)
    {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->parentCategoryId = $parentCategoryId;
    }
}