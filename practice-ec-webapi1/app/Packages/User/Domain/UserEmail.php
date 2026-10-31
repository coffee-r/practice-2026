<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserEmail
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $value) == false) {
            throw new DomainException('正しくない形式のメールアドレスです');
        }

        $this->value = $value;
    }
}