<?php

namespace App\Packages\Cart\Domain;

class ProductId
{
    public readonly int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}