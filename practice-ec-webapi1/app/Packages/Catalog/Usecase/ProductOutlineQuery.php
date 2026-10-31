<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Shared\Usecase\UsecaseException;

class ProductOutlineQuery
{
    public readonly int $page;
    public readonly int $perPage;
    public readonly int | null $categoryId;
    public readonly string | null $productKeyword;
    public readonly string | null $sort;

    private const SORT_PATTERN = [
        'HIGH_PRICE',
        'LOW_PRICE',
        'HIGH_REVIEW',
        'NEW_ARRIVAL'
    ];

    public function __construct(
        int $page,
        int $perPage,
        int | null $categoryId,
        string | null $productKeyword,
        string | null $sort
    )
    {
        if ($page < 1){
            throw new UsecaseException('ページ番号は1以上です');
        }

        if ($perPage < 1){
            throw new UsecaseException('1ページあたりの件数は1以上です');
        }

        if ($perPage > 100){
            throw new UsecaseException('1ページあたりの件数は100以内です');
        }

        if ($sort != null){
            if(in_array($sort, self::SORT_PATTERN, true) == false){
                throw new UsecaseException('指定できるソート条件ではありません');
            }
        }

        $this->page = $page;
        $this->perPage = $perPage;
        $this->categoryId = $categoryId;
        $this->productKeyword = $productKeyword;
        $this->sort = $sort;
    }

    public function offset()
    {
        return ($this->page - 1) * $this->perPage;
    }
}