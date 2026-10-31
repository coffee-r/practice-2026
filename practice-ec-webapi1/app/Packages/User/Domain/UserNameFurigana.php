<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserNameFurigana
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (empty($value) || preg_replace('/( |　)/', '', $value) == ''){
            throw new DomainException('フリガナは必須です');
        }

        if (preg_match('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', $value)){
            throw new DomainException('フリガナに絵文字は入力できません');
        }

        if (mb_strlen($value) > 30){
            throw new DomainException('フリガナは30文字以内です');
        }

        if (preg_match("/^[ァ-ヾ]+$/u", $value) == false){
            throw new DomainException('片仮名でないフリガナがあります');
        }

        $this->value = $value;
    }
}