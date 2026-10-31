<?php

namespace App\UseCases;

use App\Models\Post;

class PostStoreAction
{
    public function __invoke(int $user_id, Post $post)
    {
        // 投稿ユーザーの投稿数を取得
        $countPosts = Post::where('user_id', $user_id)->count();

        // 投稿数が5つ以上は投稿できない
        if ($countPosts >= 5) {
            abort(400, '投稿数の上限に達しました。');
        }

        // 投稿データを保存する
        $post->save();
    }
}