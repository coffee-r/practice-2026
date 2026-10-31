<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserPassword;
use PHPUnit\Framework\TestCase;

class UserPostalCode extends TestCase
{
    public function test_ブランク文字のパスワード()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('パスワードは必須です');
        $userPassword = new UserPassword('');
    }

    public function test_空白文字のみのパスワード()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('パスワードは必須です');
        $userPassword = new UserPassword(' 　');
    }

    public function test_7桁のパスワード()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('パスワードは8文字以上です');
        $userPassword = new UserPassword('1234567');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_8桁のパスワード()
    {
        $userPassword = new UserPassword('12345678');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_20桁のパスワード()
    {
        $userPassword = new UserPassword('12345678901234567890');
    }

    public function test_21桁のパスワード()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('パスワードは20文字以内です');
        $userPassword = new UserPassword('123456789012345678901');
    }

    public function test_英数字記号以外の文字_カタカナ()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('パスワードは半角英数のみです');
        $userPassword = new UserPassword('テストパスワード');
    }

    public function test_英数字記号以外の文字_漢字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('パスワードは半角英数のみです');
        $userPassword = new UserPassword('漢字漢字漢字漢字');
    }

    
}