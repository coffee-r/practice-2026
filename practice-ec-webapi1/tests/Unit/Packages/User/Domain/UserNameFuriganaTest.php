<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserNameFurigana;
use PHPUnit\Framework\TestCase;

class UserNameFuriganaTest extends TestCase
{
    public function test_フリガナがブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('フリガナは必須です');
        $userNameFurigana = new UserNameFurigana('');
    }

    public function test_フリガナが空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('フリガナは必須です');
        $userNameFurigana = new UserNameFurigana(' 　');
    }

    public function test_フリガナに絵文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('フリガナに絵文字は入力できません');
        $userNameFurigana = new UserNameFurigana('テスト🐸');
    }

    public function test_フリガナが30文字より大きい()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('フリガナは30文字以内です');
        $userNameFurigana = new UserNameFurigana('アイウエオカキクケコサシスセソタチツテトナニヌネノマミムメモラ');
    }

    public function test_フリガナに片仮名以外の文字がある()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('片仮名でないフリガナがあります');
        $userNameFurigana = new UserNameFurigana('テストてすと');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_フリガナが30文字以内()
    {
        $userNameFurigana = new UserNameFurigana('アイウエオカキクケコサシスセソタチツテトナニヌネノマミムメモ');
    }
}