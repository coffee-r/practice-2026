<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserName;
use PHPUnit\Framework\TestCase;

class UserNameTest extends TestCase
{
    public function test_ユーザー名がブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('ユーザー名は必須です');
        $userName = new UserName('');
    }

    public function test_ユーザー名が空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('ユーザー名は必須です');
        $userName = new UserName(' 　');
    }

    public function test_ユーザー名に絵文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('ユーザー名に絵文字は入力できません');
        $userName = new UserName('テスト🐸');
    }

    public function test_ユーザー名が30文字より大きい()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('ユーザー名は30文字以内です');
        $userName = new UserName('1234567890123456789012345678901');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_ユーザー名が30文字以内()
    {
        $userName = new UserName('123456789012345678901234567890');
    }
}