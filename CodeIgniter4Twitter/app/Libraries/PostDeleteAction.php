<?php

namespace App\Libraries;

use App\Models\Posts;
use Exception;

class PostDeleteAction
{
    public function __invoke(Posts $postModel, int $postId, int $loginUserId)
    {
        // 投稿を取得
        $post = $postModel->asObject()->find($postId);

        // 投稿がない場合はエラー
        if (empty($post)) {
            throw new Exception('投稿がありません', 404);
        }

        // 自分の投稿でない場合はエラー
        if ($post->user_id != $loginUserId) {
            throw new Exception('他人の投稿は削除できません', 403);
        }

        // 投稿を削除
        $postModel->delete($postId);
    }
}