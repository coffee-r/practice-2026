<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserAddress;
use PHPUnit\Framework\TestCase;

class UserAddressTest extends TestCase
{
    public function test_存在しない都道府県()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('存在しない都道府県です');
        $userAddress = new UserAddress('テスト県', 'テスト市', 'テスト番地');
    }

    public function test_市町村がブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('市区町村は必須です');
        $userAddress = new UserAddress('神奈川県', '', 'テスト番地');
    }
    
    public function test_市町村が空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('市区町村は必須です');
        $userAddress = new UserAddress('神奈川県', ' 　', 'テスト番地');
    }

    public function test_市町村に絵文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('市区町村に絵文字は入力できません');
        $userAddress = new UserAddress('神奈川県', 'テスト市🐱', 'テスト番地');
    }

    public function test_市町村が31文字以上()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('市区町村は30文字以内です');
        $userAddress = new UserAddress('神奈川県', '1234567890123456789012345678901', 'テスト番地');
    }

     /**
     * @doesNotPerformAssertions
     */
    public function test_市町村が30文字以内()
    {
        $userAddress = new UserAddress('神奈川県', '123456789012345678901234567890', 'テスト番地');
    }

    public function test_番地建物名がブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('番地・建物名・部屋番号は必須です');
        $userAddress = new UserAddress('神奈川県', 'テスト市', '');
    }
    
    public function test_番地建物名が空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('番地・建物名・部屋番号は必須です');
        $userAddress = new UserAddress('神奈川県', 'テスト市', ' 　');
    }

    public function test_番地建物名に絵文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('番地・建物名・部屋番号に絵文字は入力できません');
        $userAddress = new UserAddress('神奈川県', 'テスト市', 'テスト番地🐶');
    }

    public function test_番地建物名が31文字以上()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('番地・建物名・部屋番号は30文字以内です');
        $userAddress = new UserAddress('神奈川県', 'テスト市', '1234567890123456789012345678901');
    }

     /**
     * @doesNotPerformAssertions
     */
    public function test_番地建物名が30文字以内()
    {
        $userAddress = new UserAddress('神奈川県', 'テスト市', '123456789012345678901234567890');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_正常系()
    {
        $userAddress = new UserAddress('神奈川県', 'テスト市', 'テスト番地');
    }
}
