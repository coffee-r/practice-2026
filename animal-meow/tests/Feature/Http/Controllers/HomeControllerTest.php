<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ゲストユーザーがホームページにリクエスト()
    {
        $response = $this->get(route('home'));
        $response->assertRedirect(route('index'));
    }

    public function test_投稿が0個の状態でのホームページの表示()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('home'));
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 0));
    }

    public function test_投稿が101個の状態でのホームページの表示()
    {
        $user = User::factory()
                    ->has(Post::factory()->count(101))
                    ->create();
        $this->actingAs($user);

        $response = $this->get(route('home'));
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 100));
    }

    public function test_ゲストユーザーが自分の投稿ページにリクエスト()
    {
        $response = $this->get(route('me'));
        $response->assertRedirect(route('index'));
    }

    public function test_投稿が0個の状態での自分の投稿ページの表示()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('me'));
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 0));
    }

    public function test_自分の投稿が101個の状態での自分の投稿ページの表示()
    {
        $user = User::factory()
                    ->has(Post::factory()->count(101))
                    ->create();
        $this->actingAs($user);

        $response = $this->get(route('me'));
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 100));
    }

    public function test_自分の投稿は0個で他人の投稿が101個ある状態での自分の投稿ページの表示()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()
                    ->has(Post::factory()->count(101))
                    ->create();
        
        $response = $this->get(route('me'));
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        $response->assertInertia(fn (Assert $page) => $page->has('posts', 0));
    }
}