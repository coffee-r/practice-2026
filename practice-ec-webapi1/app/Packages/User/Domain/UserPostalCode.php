<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserPostalCode
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (empty($value) || preg_replace('/( |　)/', '', $value) == ''){
            throw new DomainException('郵便番号は必須です');
        }

        if( preg_match( '/^[0-9]+$/', $value) == false) {
            throw new DomainException('郵便番号は半角数字のみです');
        }

        if (mb_strlen($value) != 7){
            throw new DomainException('郵便番号は7桁の数字です');
        }

        $this->value = $value;
    }
}