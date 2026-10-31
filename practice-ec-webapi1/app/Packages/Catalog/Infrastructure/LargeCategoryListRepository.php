<?php

namespace App\Packages\Catalog\Infrastructure;

use App\Models\ProductCategory;
use App\Packages\Catalog\Domain\CategoryId;
use App\Packages\Catalog\Domain\CategoryName;
use App\Packages\Catalog\Domain\LargeCategory;
use App\Packages\Catalog\Domain\LargeCategoryList;
use App\Packages\Catalog\Domain\LargeCategoryListRepositoryInterface;
use App\Packages\Catalog\Domain\MediumCategory;
use App\Packages\Catalog\Domain\SmallCategory;

class LargeCategoryListRepository implements LargeCategoryListRepositoryInterface
{
    public function find() : LargeCategoryList
    {
        // カテゴリ一覧を取得
        $productCategories = ProductCategory::orderBy("sort")
                                            ->get();
        
        // 小カテゴリのドメインクラスをインスタンス化
        $smallCategoryList = $productCategories->where('depth', 3)->map(function ($item, $key) {
            return new SmallCategory(new CategoryId($item->id), new CategoryName($item->name), new CategoryId($item->parent_category_id));
        })->toArray();

        // 中カテゴリのドメインクラスをインスタンス化
        $mediumCategoryList = $productCategories->where('depth', 2)->map(function ($item, $key) {
            return new MediumCategory(new CategoryId($item->id), new CategoryName($item->name), new CategoryId($item->parent_category_id), []);
        })->toArray();

        // 大カテゴリのドメインクラスをインスタンス化
        $largeCategoryList = $productCategories->where('depth', 1)->map(function ($item, $key) {
            return new LargeCategory(new CategoryId($item->id), new CategoryName($item->name), []);
        })->toArray();

        // 中カテゴリに小カテゴリを詰めていく
        foreach ($mediumCategoryList as $mediumKey => $mediumCategory) {
            foreach ($smallCategoryList as $smallKey => $smallCategory) {
                if ($smallCategory->parentCategoryId == $mediumCategory->categoryId){
                    $mediumCategoryList[$mediumKey] = $mediumCategoryList[$mediumKey]->addSmallCategory($smallCategory);
                }
            }
        }

        // 大カテゴリに中カテゴリを詰めていく
        foreach ($largeCategoryList as $largeKey => $largeCategory) {
            foreach ($mediumCategoryList as $mediumKey => $mediumCategory) {
                if ($mediumCategory->parentCategoryId == $largeCategory->categoryId){
                    $largeCategoryList[$largeKey] = $largeCategoryList[$largeKey]->addMediumCategory($mediumCategory);
                }
            }
        }

        return new LargeCategoryList($largeCategoryList);

    }
}