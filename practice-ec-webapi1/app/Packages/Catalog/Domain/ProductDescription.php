<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class ProductDescription
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (empty($value) || preg_replace('/( |　)/', '', $value) == ''){
            throw new DomainException('商品説明は必須です');
        }

        if (mb_strlen($value) > 300){
            throw new DomainException('商品説明は300文字以内です');
        }

        $this->value = $value;
    }
}