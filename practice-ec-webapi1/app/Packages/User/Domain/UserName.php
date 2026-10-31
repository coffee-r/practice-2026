<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserName
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (empty($value) || preg_replace('/( |　)/', '', $value) == ''){
            throw new DomainException('ユーザー名は必須です');
        }

        if (preg_match('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', $value)){
            throw new DomainException('ユーザー名に絵文字は入力できません');
        }

        if (mb_strlen($value) > 30){
            throw new DomainException('ユーザー名は30文字以内です');
        }

        $this->value = $value;
    }
}