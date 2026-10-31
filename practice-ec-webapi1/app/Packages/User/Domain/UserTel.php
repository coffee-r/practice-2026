<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserTel
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if( preg_match( '/^0[0-9]{9,10}\z/', $value ) == false) {
            throw new DomainException('正しい電話番号の形式ではありません');
        }

        $this->value = $value;
    }
}