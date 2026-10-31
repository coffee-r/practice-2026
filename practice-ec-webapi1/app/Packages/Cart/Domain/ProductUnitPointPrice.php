<?php

namespace App\Packages\Cart\Domain;

use App\Packages\Shared\Domain\DomainException;

class ProductUnitPointPrice
{
    public readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 0){
            throw new DomainException('商品ポイント価格は0以上です');
        }

        $this->value = $value;
    }
}