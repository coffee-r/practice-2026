<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class ProductUnitPriceWithTax
{
    public readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 0){
            throw new DomainException('商品税込価格は0以上です');
        }

        $this->value = $value;
    }
}