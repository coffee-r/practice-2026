<?php

namespace Tests\Feature\CoffeeR\UseCases;

use App\Models\User;
use App\Models\Post;
use App\Models\TwitterUser;
use App\CoffeeR\UseCases\WithdrawalAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class WithdrawalActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_存在するユーザーを退会()
    {
        // ユーザーと投稿を作成
        $user = User::factory()
                    ->has(TwitterUser::factory()->count(1))
                    ->has(Post::factory()->count(10))
                    ->create();

        // 作成したユーザーをログインユーザーとする
        $this->actingAs($user);

        // 退会処理
        $withdrawalAction = new WithdrawalAction(new User(), new Post(), new TwitterUser());
        $withdrawalAction();

        // 退会したユーザーと投稿がないことを期待
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id
        ]);
        $this->assertDatabaseMissing('twitter_users', [
            'animal_meow_user_id' => $user->id
        ]);
    }
}