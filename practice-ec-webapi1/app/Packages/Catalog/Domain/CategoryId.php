<?php

namespace App\Packages\Catalog\Domain;

class CategoryId
{
    public readonly int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}