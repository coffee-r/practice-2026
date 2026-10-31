<?php

namespace App\Packages\Cart\Domain;

use App\Packages\Shared\Domain\DomainException;

class ProductQuantity
{
    public readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 1){
            throw new DomainException('商品数量は1以上です');
        }

        $this->value = $value;
    }

    public function add(ProductQuantity $addQuantity)
    {
        return new ProductQuantity($this->value + $addQuantity->value);
    }
}