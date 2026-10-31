<?php

namespace Tests\Feature\CoffeeR\UseCases;

use App\CoffeeR\UseCases\PostDeleteAction;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_存在しない投稿の削除()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('この投稿は存在しません。');
        $postDeleteAction = new PostDeleteAction(new Post());
        $postDeleteAction(0);
    }

    public function test_他人の投稿の削除()
    {
        $user = User::factory()->create(['id' => 1]);
        $otherUser = User::factory()->create(['id' => 2]);
        $post = Post::factory()->create(['id' => 1, 'user_id' => $otherUser->id]);
        $this->actingAs($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('この投稿は投稿者のみが削除できます。');
        $postDeleteAction = new PostDeleteAction(new Post());
        $postDeleteAction(1);
    }

    public function test_自分の投稿の削除()
    {
        $user = User::factory()->create(['id' => 1]);
        $post = Post::factory()->create(['id' => 1, 'user_id' => $user->id]);
        $this->actingAs($user);

        // 一応削除対象の投稿があることを事前に確認
        $this->assertDatabaseHas('posts', [
            'id' => 1,
        ]);

        $postDeleteAction = new PostDeleteAction(new Post());
        $postDeleteAction(1);

        $this->assertDatabaseMissing('posts', [
            'id' => 1,
        ]);
    }
}