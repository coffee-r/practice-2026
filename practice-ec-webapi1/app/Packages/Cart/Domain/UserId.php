<?php

namespace App\Packages\Cart\Domain;

class UserId
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}