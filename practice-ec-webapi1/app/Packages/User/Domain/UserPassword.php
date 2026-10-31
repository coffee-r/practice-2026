<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserPassword
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (empty($value) || preg_replace('/( |　)/', '', $value) == ''){
            throw new DomainException('パスワードは必須です');
        }

        if (preg_match('/[a-zA-Z0-9]+$/', $value) == false){
            throw new DomainException('パスワードは半角英数のみです');
        }

        if (mb_strlen($value) < 8){
            throw new DomainException('パスワードは8文字以上です');
        }

        if (mb_strlen($value) > 20){
            throw new DomainException('パスワードは20文字以内です');
        }

        $this->value = $value;
    }
}