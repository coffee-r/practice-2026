<?php

namespace Tests\Feature\Http\Controllers;

use App\CoffeeR\Domain\Tweet;
use App\CoffeeR\UseCases\TweetAction;
use App\Exceptions\TwitterClientException;
use App\Models\Animal;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Mockery\MockInterface;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ゲストユーザーが新規投稿フォーム閲覧()
    {
        $response = $this->get(route('post.create'));
        $response->assertRedirect(route('index'));
    }

    public function test_ゲストユーザーが新規投稿()
    {
        $response = $this->post(route('post.store'));
        $response->assertRedirect(route('index'));
    }

    public function test_ゲストユーザーが投稿削除()
    {
        $response = $this->delete(route('post.destroy', 0));
        $response->assertRedirect(route('index'));
    }

    public function test_動物マスタが空の状態で新規投稿フォーム閲覧()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->expectException(\Exception::class);
        $response = $this->withoutExceptionHandling()->get(route('post.create'));
    }

    public function test_投稿フォームのバリデーションで失敗()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('post.store'), ['animalId' => '', 'message' => 'test_message']);
        $response->assertSessionHasErrors(['animalId']);

        $response = $this->post(route('post.store'), ['animalId' => 'test', 'message' => 'test_message']);
        $response->assertSessionHasErrors(['animalId']);

        $response = $this->post(route('post.store'), ['animalId' => '1', 'message' => '']);
        $response->assertSessionHasErrors(['message']);

        $messageLimitOver = Str::random(101);
        $response = $this->post(route('post.store'), ['animalId' => '1', 'message' => $messageLimitOver]);
        $response->assertSessionHasErrors(['message']);
    }

    public function test_動物マスタある状態で新規投稿フォーム閲覧()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $animals = Animal::factory(10)->create();
        $response = $this->get(route('post.create'));

        $response->assertInertia(fn (Assert $page) => $page->component('Post'));
        $response->assertInertia(fn (Assert $page) => $page->has('animals', 10));
    }

    public function test_新規投稿()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $animal = Animal::factory()->create(['id' => 1, 'name' => 'ねこ', 'language' => 'にゃ〜ん🐱']);

        $response = $this->post(route('post.store'), ['animalId' => 1, 'message' => 'にゃん🐱']);
        $response->assertSessionHas('successMessages');
        $response->assertRedirect(route('home'));
    }

    public function test_投稿を削除()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->delete(route('post.destroy', $post->id));
        $response->assertRedirect(url()->previous());
    }

    // TwitterAPI有料化のため廃止
    // public function test_新規投稿及びツイート()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $animal = Animal::factory()->create(['id' => 1, 'name' => 'ねこ', 'language' => 'にゃ〜ん🐱']);

    //     $this->instance(
    //         TweetAction::class,
    //         Mockery::mock(TweetAction::class, function(MockInterface $mock) {
    //             $mock->shouldReceive('__invoke')
    //                  ->once()
    //                  ->andReturn(new Tweet('にゃん🐱', 'https://example.com'));
    //         })
    //     );

    //     $response = $this->post(route('post.store'), ['animalId' => 1, 'message' => 'にゃん🐱', 'withTweet' => true]);
    //     $response->assertSessionHas('successMessages');
    //     $response->assertRedirect(route('home'));
    // }

    // TwitterAPI有料化のため廃止
    // public function test_ツイートに失敗()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $animal = Animal::factory()->create(['id' => 1, 'name' => 'ねこ', 'language' => 'にゃ〜ん🐱']);

    //     $this->instance(
    //         TweetAction::class,
    //         Mockery::mock(TweetAction::class, function(MockInterface $mock) {
    //             $mock->shouldReceive('__invoke')
    //                  ->once()
    //                  ->andThrowExceptions([new TwitterClientException()]);
    //         })
    //     );

    //     $response = $this->post(route('post.store'), ['animalId' => 1, 'message' => 'にゃん🐱', 'withTweet' => true]);
    //     $response->assertSessionHas('failMessages');
    //     $response->assertRedirect(route('home'));
    // }
}