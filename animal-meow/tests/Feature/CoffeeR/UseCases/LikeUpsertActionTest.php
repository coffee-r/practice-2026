<?php

namespace Tests\Feature\CoffeeR\UseCases;

use App\CoffeeR\UseCases\LikeUpsertAction;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Exceptions\PostNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class LikeUpsertActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_存在しない投稿にいいね()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // 作成したユーザーをログインユーザーとする
        $this->actingAs($user);

        $likeUpsertAction = new LikeUpsertAction(new Post(), new Like());
        $this->expectException(PostNotFoundException::class);
        $likeUpsertAction(0);
    }

    public function test_投稿に投稿ユーザーが新規にいいねする()
    {
        // ユーザーと投稿を作成
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'like_total_count' => 0]);

        // 作成したユーザーをログインユーザーとする
        $this->actingAs($user);

        // 1回いいねする
        $likeUpsertAction = new LikeUpsertAction(new Post(), new Like());
        $likeUpsertAction($post->id);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'like_total_count' => 1
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'like_count' => 1
        ]);
    }

    public function test_投稿に投稿ユーザーが追加でいいねする()
    {
        // ユーザーと投稿を作成
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'like_total_count' => 0]);

        // 作成したユーザーをログインユーザーとする
        $this->actingAs($user);

        // 2回いいねする
        $likeUpsertAction = new LikeUpsertAction(new Post(), new Like());
        $likeUpsertAction($post->id);
        $likeUpsertAction($post->id);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'like_total_count' => 2
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'like_count' => 2
        ]);
    }

    public function test_投稿に投稿者でないユーザーがいいねする()
    {
        // ユーザーと投稿を作成
        $postUser = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postUser->id, 'like_total_count' => 0]);

        // 投稿者でないユーザーをログインユーザーとする
        $this->actingAs($otherUser);

        // 1回いいねする
        $likeUpsertAction = new LikeUpsertAction(new Post(), new Like());
        $likeUpsertAction($post->id);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'like_total_count' => 1
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $otherUser->id,
            'post_id' => $post->id,
            'like_count' => 1
        ]);
    }
}