<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testDelete投稿者自身が削除()
    {
        $post = Post::factory()->create();
        $user = User::find($post->user_id);

        $this->actingAs($user)
            ->delete('/posts/'.$post->id)
            ->assertStatus(302);
    }

    public function testDelete投稿者以外が削除()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete('/posts/'.$post->id)
            ->assertStatus(403);
    }
}