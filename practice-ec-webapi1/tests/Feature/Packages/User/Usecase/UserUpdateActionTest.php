<?php

namespace Tests\Feature\Packages\User;

use App\Models\User;
use App\Packages\Shared\Usecase\UsecaseException;
use App\Packages\User\Domain\UserService;
use App\Packages\User\Infrastructure\UserRepository;
use App\Packages\User\Usecase\UserData;
use App\Packages\User\Usecase\UserUpdateAction;
use App\Packages\User\Usecase\UserUpdateCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_正常更新()
    {
        $user = User::factory()->create();
        $userUpdateCommand = new UserUpdateCommand(
            $user->id,
            'testupdate',
            'テストアップデート',
            '男性',
            2000,
            1,
            'updated@test.co.jp',
            'passwordupdate',
            '8888888',
            '福島県',
            'テスト市',
            'テスト番地',
            '09088888888',
            false
        );

        $userUpdateAction = new UserUpdateAction(new UserRepository(), new UserService(new UserRepository()));
        $userData = $userUpdateAction($userUpdateCommand);

        $this->assertInstanceOf(UserData::class, $userData);
        $this->assertDatabaseHas(
            'users',
            [
                'id' => $user->id,
                'name' => 'testupdate',
                'name_furigana' => 'テストアップデート',
                'gender' => '男性',
                'birthday_year' => 2000,
                'birthday_month' => 1,
                'email' => 'updated@test.co.jp',
                'password' => 'passwordupdate',
                'postal_code' => '8888888',
                'address_prefectures' => '福島県',
                'address_municipalities' => 'テスト市',
                'address_others' => 'テスト番地',
                'tel' => '09088888888',
                'email_magazine_subscription' => 0
            ]
        );

    }

    public function test_存在しないユーザーの更新()
    {
        $userUpdateCommand = new UserUpdateCommand(
            1,
            'testupdate',
            'テストアップデート',
            '男性',
            2000,
            1,
            'updated@test.co.jp',
            'passwordupdate',
            '8888888',
            '福島県',
            'テスト市',
            'テスト番地',
            '09088888888',
            false
        );

        $userUpdateAction = new UserUpdateAction(new UserRepository(), new UserService(new UserRepository()));

        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('ユーザーは存在しません');
        $userData = $userUpdateAction($userUpdateCommand);
    }

    public function test_ユーザー間のメールアドレスが重複しようとした時()
    {
        $user1 = User::factory()->create(['email' => 'test1@test.co.jp']);
        $user2 = User::factory()->create(['email' => 'test2@test.co.jp']);

        $userUpdateCommand = new UserUpdateCommand(
            $user2->id,
            'testupdate',
            'テストアップデート',
            '男性',
            2000,
            1,
            'test1@test.co.jp',
            'passwordupdate',
            '8888888',
            '福島県',
            'テスト市',
            'テスト番地',
            '09088888888',
            false
        );

        $userUpdateAction = new UserUpdateAction(new UserRepository(), new UserService(new UserRepository()));

        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('このメールアドレスは既に使用されています');
        $userData = $userUpdateAction($userUpdateCommand);
    }
}
