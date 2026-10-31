<?php

namespace App\Packages\Catalog\Infrastructure;

use App\Packages\Catalog\Usecase\ProductOutlineData;
use App\Packages\Catalog\Usecase\ProductOutlinePagenationData;
use App\Packages\Catalog\Usecase\ProductOutlineQuery;
use App\Packages\Catalog\Usecase\ProductOutlineQueryServiceInterface;
use App\Packages\Shared\Usecase\PagenationData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductOutlineQueryService implements ProductOutlineQueryServiceInterface
{
    public function findPagenator(ProductOutlineQuery $productOutlineQuery) : ProductOutlinePagenationData | null
    {
        $productsQuery = DB::table('products');

        // カテゴリ絞り込み
        if (!empty($productOutlineQuery->categoryId)){
            $productsQuery = $productsQuery->where('category_id', '=', $productOutlineQuery->categoryId);
        }

        // キーワード検索絞り込み
        if (!empty($productOutlineQuery->productKeyword)){
            $productsQuery = $productsQuery->where('name', 'like', '%'.$productOutlineQuery->productKeyword.'%');
        }

        // 合計件数を先に取得
        $total = $productsQuery->count();

        // ソート条件設定
        if ($productOutlineQuery->sort === 'HIGH_PRICE'){
            $productsQuery = $productsQuery->orderByDesc('price_with_tax');
        }else if($productOutlineQuery->sort === 'LOW_PRICE'){
            $productsQuery = $productsQuery->orderBy('price_with_tax');
        }else if($productOutlineQuery->sort === 'HIGH_REVIEW'){
            $productsQuery = $productsQuery->orderByDesc('review_score_average');
        }else if($productOutlineQuery->sort === 'NEW_ARRIVAL'){
            $productsQuery = $productsQuery->orderByDesc('id');
        }

        // オフセット設定
        $productsQuery = $productsQuery->offset($productOutlineQuery->offset());

        // 取得件数指定
        $productsQuery = $productsQuery->limit($productOutlineQuery->perPage);

        // 商品取得
        $products = $productsQuery->get();

        if(empty($products)){
            return null;
        }

        $productOutlineDataList = [];
        
        foreach ($products as $key => $product) {
            $productOutlineDataList[] = new ProductOutlineData(
                $product->id,
                $product->name,
                $product->category_id,
                $product->price_with_tax,
                $product->point_price,
                $product->review_score_average
            );
        }

        $pagenationData = new PagenationData($total, $productOutlineQuery->page, $productOutlineQuery->perPage);

        return new ProductOutlinePagenationData($productOutlineDataList, $pagenationData);
        
    }
}