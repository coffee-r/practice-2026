<?php

namespace App\CoffeeR\UseCases;

use App\Models\Post;
use App\Models\Like;
use App\Models\TwitterUser;
use App\Exceptions\PostNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * いいね追加処理
 */
class LikeUpsertAction
{
    private $post;
    private $like;

    public function __construct(Post $post, Like $like)
    {
        $this->post = $post;
        $this->like = $like;
    }

    public function __invoke(int $post_id): void
    {
        // 投稿を取得
        $post = $this->post->find($post_id);

        // 存在しない投稿にはいいねはできない
        if (empty($post)) {
            throw new PostNotFoundException("投稿id「".$post_id."」が見つかりませんでした。");
        }

        // ユーザーのいいねを取得
        $like = $this->like
                     ->where('post_id', $post_id)
                     ->where('user_id', Auth::id())
                     ->first();

        // トランザクション
        DB::beginTransaction();

        // 投稿のtotalいいねを+1
        $post->like_total_count += 1;
        $post->update();

        // ユーザーのいいねが既にある場合はいいねを+1、
        // ユーザーのいいねが既にある場合は新規作成
        if ($like) {
            $like->like_count += 1;
            $like->update();
        } else {
            $like = new Like();
            $like->user_id = Auth::id();
            $like->post_id = $post_id;
            $like->like_count = 1;
            $like->save();
        }

        // コミット
        DB::commit();

        return ;
    }
}