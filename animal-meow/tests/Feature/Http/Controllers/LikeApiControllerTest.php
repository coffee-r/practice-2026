<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ゲストユーザーが投稿にいいね()
    {
        $post = Post::factory()
                    ->for(User::factory()->create())
                    ->create();
        
        $response = $this->postJson(route('post.like.upsert', $post->id));
        $response->assertStatus(401);
    }

    public function test_投稿にいいね()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()
                    ->for($user)
                    ->create();
        
        $response = $this->postJson(route('post.like.upsert', $post->id));
        $response->assertStatus(200);
    }

    public function test_存在しない投稿にいいね()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->postJson(route('post.like.upsert', 0));
        $response->assertStatus(403);
    }
}