<?php

namespace App\Packages\Catalog\Domain;

use App\Packages\Shared\Domain\DomainException;

class CategoryName
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (empty($value) || preg_replace('/( |　)/', '', $value) == ''){
            throw new DomainException('カテゴリ名は必須です');
        }

        if (preg_match('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', $value)){
            throw new DomainException('カテゴリ名に絵文字は入力できません');
        }

        if (mb_strlen($value) > 20){
            throw new DomainException('カテゴリ名は20文字以内です');
        }

        $this->value = $value;
    }
}