<?php

namespace App\Packages\Cart\Domain;

class CartId
{
    public readonly int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}