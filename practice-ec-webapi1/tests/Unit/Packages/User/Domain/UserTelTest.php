<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserTel;
use PHPUnit\Framework\TestCase;

class UserTelTest extends TestCase
{
    public function test_9桁の電話番号()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('正しい電話番号の形式ではありません');
        $userTel = new UserTel('012345678');
    }

    public function test_12桁の電話番号()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('正しい電話番号の形式ではありません');
        $userTel = new UserTel('012345678901');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_10桁の電話番号()
    {
        $userTel = new UserTel('0123456789');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_11桁の電話番号()
    {
        $userTel = new UserTel('01234567890');
    }

    public function test_先頭が0でない電話番号()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('正しい電話番号の形式ではありません');
        $userTel = new UserTel('1234567890');
    }
}