<?php

namespace Tests\Unit\Packages\User\Domain;

use App\Packages\Shared\Domain\DomainException;
use App\Packages\User\Domain\UserGender;
use PHPUnit\Framework\TestCase;

class UserGenderTest extends TestCase
{
    public function test_存在しない性別()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('存在しない性別です');
        $userGender = new UserGender('テスト');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_正常系()
    {
        $userGender = new UserGender('男性');
        $userGender = new UserGender('女性');
        $userGender = new UserGender('その他');
    }
}