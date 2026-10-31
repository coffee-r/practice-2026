<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ゲストユーザーがトップページにリクエスト()
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
    }

    public function test_投稿が0の状態でのトップページの表示()
    {
        $response = $this->get(route('index'));
        $response->assertInertia(fn (Assert $page) => $page->component('Index'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 0));
    }

    public function test_投稿が11個ある状態でのトップページの表示()
    {
        $user = User::factory()
                    ->has(Post::factory()->count(11))
                    ->create();

        $response = $this->get(route('index'));
        $response->assertInertia(fn (Assert $page) => $page->component('Index'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 10));
    }

    public function test_ログインユーザーがトップページにリクエスト()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('index'));
        $response->assertRedirect(route('home'));
    }
}