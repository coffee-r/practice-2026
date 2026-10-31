<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserBirthday;
use PHPUnit\Framework\TestCase;

class UserBirthdayTest extends TestCase
{
    public function test_生年が4桁未満()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('生年は4桁以上の数字です');
        $userBirthday = new UserBirthday(999, 1);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_生年が4桁以上()
    {
        $userBirthday = new UserBirthday(1000, 1);
    }

    public function test_生月が1未満()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('生月は1~12までの数字です');
        $userBirthday = new UserBirthday(1000, 0);
    }

    public function test_生月が12よりも大きい()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('生月は1~12までの数字です');
        $userBirthday = new UserBirthday(1000, 13);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_生月が1から12の範囲()
    {
        for ($i=1; $i <= 12 ; $i++) { 
            $userBirthday = new UserBirthday(1000, $i);
        }
    }
}