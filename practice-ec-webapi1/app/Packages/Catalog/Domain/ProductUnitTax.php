<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class ProductUnitTax
{
    public readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 0){
            throw new DomainException('商品単価税額は0以上です');
        }

        $this->value = $value;
    }
}