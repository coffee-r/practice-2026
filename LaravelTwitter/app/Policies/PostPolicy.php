<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function delete(User $user, Post $post): Response
    {
        if ($post->user_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('この投稿の削除権限がありません。');
    }
}
