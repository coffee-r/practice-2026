<?php

namespace App\CoffeeR\UseCases;

use App\Models\User;
use App\Models\Post;
use App\Models\TwitterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * 退会処理
 */
class WithdrawalAction
{
    private $user;
    private $post;
    private $twitterUser;

    public function __construct(User $user, Post $post, TwitterUser $twitterUser)
    {
        $this->user = $user;
        $this->post = $post;
        $this->twitterUser = $twitterUser;
    }

    public function __invoke(): void
    {
        // トランザクション
        DB::beginTransaction();

        // twitterのtokenを削除
        $this->twitterUser->where('animal_meow_user_id', Auth::id())->delete();

        // 投稿テーブルを削除
        $this->post->where('user_id', Auth::id())->delete();

        // ユーザーを削除
        $this->user->where('id', Auth::id())->delete();

        // コミット
        DB::commit();
    }
}