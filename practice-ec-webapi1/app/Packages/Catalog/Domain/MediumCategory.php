<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class MediumCategory
{
    public readonly CategoryId $categoryId;
    public readonly CategoryName $categoryName;
    public readonly CategoryId $parentCategoryId;
    /** @var array<SmallCategory> */
    public readonly array $smallCategoryList;

    public function __construct(CategoryId $categoryId, CategoryName $categoryName, CategoryId $parentCategoryId, array $smallCategoryList)
    {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->parentCategoryId = $parentCategoryId;

        foreach ($smallCategoryList as $key => $smallCategory) {
            if(($smallCategory instanceof SmallCategory) == false){
                throw new DomainException('中カテゴリには小カテゴリを関連付けることができます');
            }
        }
        $this->smallCategoryList = $smallCategoryList;
    }

    public function addSmallCategory(SmallCategory $smallCategory)
    {
        $smallCategoryList = $this->smallCategoryList;
        $smallCategoryList[] = $smallCategory;
        return new MediumCategory($this->categoryId, $this->categoryName, $this->parentCategoryId, $smallCategoryList);
    }
}