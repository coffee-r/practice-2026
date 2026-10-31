<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class ProductReviewScoreAverage
{
    public readonly int | null $value;

    public function __construct(int | null $value)
    {
        if ($value == null){
            $this->value = $value;
            return;
        }

        if ($value < 1 || $value > 5){
            throw new DomainException('商品レビュー平均値は1から5までの値です');
        }
        
        $this->value = round($value, 1);
    }
}