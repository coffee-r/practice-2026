<?php

namespace Tests\Feature\CoffeeR\UseCases;

use App\CoffeeR\UseCases\UserUpsertWithTwitterAction;
use App\Models\User;
use App\Models\TwitterUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;


class UserUpsertWithTwitterActionTest extends TestCase
{
    use RefreshDatabase;

    private $socialiteUserMock;

    public function setUp() :void
    {
        parent::setUp();

        // socialite経由で取得するSNSのユーザーインスタンスのモックを作成
        $this->socialiteUserMock = Mockery::mock(\Laravel\Socialite\Contracts\User::class);
        $this->socialiteUserMock->shouldReceive('getId')
                      ->andReturn('test_twitter_id');
        $this->socialiteUserMock->shouldReceive('getName')
                      ->once()
                      ->andReturn('test_name');
        $this->socialiteUserMock->shouldReceive('getAvatar')
                      ->once()
                      ->andReturn('https://example.com/test.jpg');
        $this->socialiteUserMock->shouldReceive('getNickname')
                      ->once()
                      ->andReturn('test_nickname');
        $this->socialiteUserMock->token = 'test_access_token';
        $this->socialiteUserMock->expiresIn = 7200;
        $this->socialiteUserMock->refreshToken = 'test_refresh_token';

        // テスト検証用に日付を設定
        Carbon::setTestNow(new Carbon('2022-01-01 00:00:00'));

    }

    public function tearDown() :void
    {
        parent::tearDown();

        // 日付を元に戻す
        Carbon::setTestNow(null);
    }

    public function test_新規ユーザー登録()
    {
        // レコードがないことを一応確認
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('twitter_users', 0);

        // ユーザーの新規登録
        $userUpsertWithTwitterAction = new UserUpsertWithTwitterAction(new User(), new TwitterUser());
        $user = $userUpsertWithTwitterAction($this->socialiteUserMock);

        // 登録したユーザーが存在することを期待
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test_name',
            'avatar_image_url' => 'https://example.com/test.jpg'
        ]);
        $this->assertDatabaseHas('twitter_users', [
            'twitter_id' => 'test_twitter_id',
            'nickname' => 'test_nickname',
            // 'access_token' => Crypt::encryptString('test_access_token'),
            'access_token_time_limit' => '2022-01-01 02:00:00',
            // 'refresh_token' => Crypt::encryptString('test_refresh_token'),
        ]);

        // アクセストークンとリフレッシュトークンの検証
        // ※Eloquentを介して暗号化/復号化しているためassertDatabaseHasだとうまく検証できなさそう
        $twitterUser = TwitterUser::where('twitter_id', 'test_twitter_id')->first();
        $this->assertEquals('test_access_token', $twitterUser->access_token);
        $this->assertEquals('test_refresh_token', $twitterUser->refresh_token);
    }

    public function test_既存ユーザー更新()
    {
        // ユーザーの作成
        $user = User::factory()->create([
            'id' => 1,
            'name' =>'before',
            'avatar_image_url' => 'before'
        ]);
        $twitterUser = TwitterUser::factory()->create([
            'twitter_id' => 'test_twitter_id',
            'animal_meow_user_id' => $user->id,
            'access_token' => 'before',
            'access_token_time_limit' => '1999-01-01 00:00:00',
            'refresh_token' => 'before'
        ]);

        // ユーザーの更新
        $userUpsertWithTwitterAction = new UserUpsertWithTwitterAction(new User(), new TwitterUser());
        $user = $userUpsertWithTwitterAction($this->socialiteUserMock);

        // 更新されたレコードがあることを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test_name',
            'avatar_image_url' => 'https://example.com/test.jpg'
        ]);

        $this->assertDatabaseHas('twitter_users', [
            'twitter_id' => 'test_twitter_id',
            'nickname' => 'test_nickname',
            // 'access_token' => Crypt::encryptString('test_access_token'),
            'access_token_time_limit' => '2022-01-01 02:00:00',
            // 'refresh_token' => Crypt::encryptString('test_refresh_token'),
        ]);

        // アクセストークンとリフレッシュトークンの検証
        // ※Eloquentを介して暗号化/復号化しているためassertDatabaseHasだとうまく検証できなさそう
        $twitterUser = TwitterUser::where('twitter_id', 'test_twitter_id')->first();
        $this->assertEquals('test_access_token', $twitterUser->access_token);
        $this->assertEquals('test_refresh_token', $twitterUser->refresh_token);
    }
}