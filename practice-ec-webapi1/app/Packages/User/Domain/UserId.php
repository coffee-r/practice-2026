<?php

namespace App\Packages\User\Domain;

class UserId
{
    public readonly int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}