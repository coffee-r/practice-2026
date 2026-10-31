<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class LargeCategory
{
    public readonly CategoryId $categoryId;
    public readonly CategoryName $categoryName;
    /** @var array<MediumCategory> */
    public readonly array $mediumCategoryList;

    public function __construct(CategoryId $categoryId, CategoryName $categoryName, array $mediumCategoryList)
    {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;

        foreach ($mediumCategoryList as $key => $mediumCategory) {
            if(($mediumCategory instanceof MediumCategory) == false){
                throw new DomainException('大カテゴリには中カテゴリを関連付けることができます');
            }
        }
        $this->mediumCategoryList = $mediumCategoryList;
    }

    public function addMediumCategory(MediumCategory $mediumCategory)
    {
        $mediumCategoryList = $this->mediumCategoryList;
        $mediumCategoryList[] = $mediumCategory;
        return new LargeCategory($this->categoryId, $this->categoryName, $mediumCategoryList);
    }
}