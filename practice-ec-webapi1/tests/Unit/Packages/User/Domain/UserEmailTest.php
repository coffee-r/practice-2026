<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserEmail;
use PHPUnit\Framework\TestCase;

class UserEmailTest extends TestCase
{
    public function test_メールアドレスの形式でない()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('正しくない形式のメールアドレスです');
        $userEmail = new UserEmail('test');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_正常系()
    {
        $userEmail = new UserEmail('test@test.co.jp');
    }
}