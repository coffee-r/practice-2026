<?php

namespace Tests\Feature\CoffeeR\UseCases;

use App\CoffeeR\UseCases\PostStoreAction;
use App\Exceptions\PostStoreException;
use App\Models\Animal;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostStoreActionTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $animal;

    public function setUp() :void
    {
        parent::setUp();

        // ユーザーを作成
        $this->user = User::factory()->create(['id' => 1]);

        // 作成したユーザーをログインユーザーとする
        $this->actingAs($this->user);

        // 動物を作成
        $this->animal = Animal::factory()->create(['id' => 1, 'name' => 'ねこ', 'language' => 'にゃんやっしニャンヤッシ〜ー！？🐱']);
    }

    public function test_存在しない動物idを使って投稿()
    {
        $this->expectException(PostStoreException::class);
        $this->expectExceptionMessage('動物が存在しません');
        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $postStoreAction(0, 'test_message');
    }

    public function test_空文字で投稿()
    {
        $this->expectException(PostStoreException::class);
        $this->expectExceptionMessage('空文字だけの投稿はできません。');
        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $postStoreAction(1, '');
    }

    public function test_スペースのみで投稿()
    {
        $this->expectException(PostStoreException::class);
        $this->expectExceptionMessage('空白文字だけでの投稿はできません。');
        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $postStoreAction(1, ' ');
    }

    public function test_投稿メッセージ文字数上限100文字を超えて投稿()
    {
        // 101文字のメッセージ
        $message = '';
        for($i=0;$i<101;$i++){
            $message .= 'に';
        }

        $this->expectException(PostStoreException::class);
        $this->expectExceptionMessage('投稿メッセージは100文字までです。');
        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $postStoreAction(1, $message);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_投稿メッセージ文字数上限100文字ぴったりで投稿()
    {
        // 100文字のメッセージ
        $message = '';
        for($i=0;$i<100;$i++){
            $message .= 'に';
        }

        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $postStoreAction(1, $message);
    }

    public function test_使用できない文字を使って投稿()
    {
        // 猫語に象語を混ぜたもの
        $message = 'にゃ〜ん パオーン';

        $this->expectException(PostStoreException::class);
        $this->expectExceptionMessage('使用することができない文字です。');
        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $postStoreAction(1, 'にゃ〜ん パオーン');
    }

    public function test_正常系()
    {
        $message = 'にゃ〜ん！ ニャーン？ 🐱 ';

        $postStoreAction = new PostStoreAction(new Post(), new Animal());
        $post = $postStoreAction(1, $message);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $this->user->id,
            'animal_id' => $this->animal->id,
            'message' => $message
        ]);
    }
}