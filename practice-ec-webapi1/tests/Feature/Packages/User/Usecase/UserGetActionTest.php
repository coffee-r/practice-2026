<?php

namespace Tests\Feature\Packages\User;

use App\Models\User;
use App\Packages\Shared\Usecase\UsecaseException;
use App\Packages\User\Domain\UserService;
use App\Packages\User\Infrastructure\UserRepository;
use App\Packages\User\Usecase\UserData;
use App\Packages\User\Usecase\UserGetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserGetActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_存在するユーザー()
    {
        $user = User::factory()->create();
        $userGetAction = new UserGetAction(new UserRepository, new UserService(new UserRepository));
        $userData = $userGetAction(1);
        $this->assertInstanceOf(UserData::class, $userData);
    }

    public function test_存在しないユーザー()
    {
        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('ユーザーは存在しません');
        $userGetAction = new UserGetAction(new UserRepository, new UserService(new UserRepository));
        $userData = $userGetAction(1);
    }
}
