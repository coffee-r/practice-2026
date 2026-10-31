<?php

namespace Tests\Feature\Packages\User;

use App\Models\User;
use App\Packages\Shared\Usecase\UsecaseException;
use App\Packages\User\Domain\UserService;
use App\Packages\User\Infrastructure\UserFactory;
use App\Packages\User\Infrastructure\UserRepository;
use App\Packages\User\Usecase\UserData;
use App\Packages\User\Usecase\UserRegisterAction;
use App\Packages\User\Usecase\UserRegisterCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegisterActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_新規登録()
    {
        $userRegisterCommand = new UserRegisterCommand(
            'test',
            'テスト',
            '女性',
            1999,
            12,
            'test@test.co.jp',
            'password',
            '9999999',
            '神奈川県',
            'テスト市',
            'テスト番地',
            '09099999999',
            true
        );
        
        $userRegisterAction = new UserRegisterAction(new UserRepository(), new UserFactory(), new UserService(new UserRepository));
        $userData = $userRegisterAction($userRegisterCommand);

        $this->assertInstanceOf(UserData::class, $userData);
        $this->assertDatabaseHas(
            'users',
            [
                'name' => 'test',
                'name_furigana' => 'テスト',
                'gender' => '女性',
                'birthday_year' => 1999,
                'birthday_month' => 12,
                'email' => 'test@test.co.jp',
                'password' => 'password',
                'postal_code' => '9999999',
                'address_prefectures' => '神奈川県',
                'address_municipalities' => 'テスト市',
                'address_others' => 'テスト番地',
                'tel' => '09099999999',
                'email_magazine_subscription' => 1
            ]
        );
        
    }

    public function test_重複したメールアドレスで登録()
    {
        $user = User::factory()->create(['email' => 'test@test.co.jp']);

        $userRegisterCommand = new UserRegisterCommand(
            'test',
            'テスト',
            '女性',
            1999,
            12,
            'test@test.co.jp',
            'password',
            '9999999',
            '神奈川県',
            'テスト',
            'テスト',
            '09099999999',
            true
        );
        
        $userRegisterAction = new UserRegisterAction(new UserRepository(), new UserFactory(), new UserService(new UserRepository));

        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('このメールアドレスは既に使用されています');
        $userData = $userRegisterAction($userRegisterCommand);
    }
}
