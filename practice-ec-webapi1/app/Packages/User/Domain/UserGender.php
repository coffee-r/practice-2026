<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserGender
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (in_array($value, self::GENDER) === false){
            throw new DomainException('存在しない性別です');
        }

        $this->value = $value;
    }

    private const GENDER = [
        '男性',
        '女性',
        'その他'
    ];
}