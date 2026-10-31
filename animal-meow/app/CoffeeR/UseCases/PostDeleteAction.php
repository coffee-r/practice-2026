<?php

namespace App\CoffeeR\UseCases;

use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostDeleteAction
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function __invoke(int $post_id)
    {
        // 投稿を取得
        $post = $this->post->find($post_id);

        // 存在しない投稿は削除できない
        if (!$post) {
            throw new \Exception('この投稿は存在しません。');
        }

        // 他人の投稿は削除できない
        if ($post->user_id !== Auth::id()) {
            throw new \Exception('この投稿は投稿者のみが削除できます。');
        }

        // 投稿を削除
        $post->delete();
    }
}