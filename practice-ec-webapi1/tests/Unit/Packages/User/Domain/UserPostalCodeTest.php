<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserPostalCode;
use PHPUnit\Framework\TestCase;

class UserPostalCodeTest extends TestCase
{
    public function test_郵便番号がブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('郵便番号は必須です');
        $userName = new UserPostalCode('');
    }

    public function test_郵便番号が空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('郵便番号は必須です');
        $userName = new UserPostalCode(' 　');
    }

    public function test_郵便番号が6桁の半角数字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('郵便番号は7桁の数字です');
        $userName = new UserPostalCode('123456');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_郵便番号が7桁の半角数字()
    {
        $userName = new UserPostalCode('1234567');
    }

    public function test_郵便番号が8桁の半角数字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('郵便番号は7桁の数字です');
        $userName = new UserPostalCode('12345678');
    }
}