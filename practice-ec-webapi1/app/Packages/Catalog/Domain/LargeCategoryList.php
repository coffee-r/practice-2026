<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class LargeCategoryList
{
    /** @var array<LargeCategory> */
    public readonly array $value;

    public function __construct(array $largeCategoryList)
    {
        foreach ($largeCategoryList as $key => $largeCategory) {
            if(($largeCategory instanceof LargeCategory) == false){
                throw new DomainException('大カテゴリリストには大カテゴリを関連付けることができます');
            }
        }
        $this->value = $largeCategoryList;
    }

    public function addLargeCategory(LargeCategory $largeCategory)
    {
        $largeCategoryList = $this->value;
        $largeCategoryList[] = $largeCategory;
        return new LargeCategoryList($largeCategoryList);
    }
}