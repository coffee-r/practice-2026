<?php

namespace App\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;

class UserBirthday
{
    public readonly int $year;
    public readonly int $month;

    public function __construct(int $year, int $month)
    {
        if ($year < 1000) {
            throw new DomainException("生年は4桁以上の数字です");
        }

        if ($month < 1 || $month > 12) {
            throw new DomainException("生月は1~12までの数字です");
        }

        $this->year = $year;
        $this->month = $month;
    }
}