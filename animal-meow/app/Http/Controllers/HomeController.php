<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * ホーム画面アクション
     *
     * @return void
     */
    public function index()
    {
        // 最新の投稿を100件取得
        $posts = Post::join('users', 'users.id', '=', 'posts.user_id')
                     ->select(['posts.id as post_id', 'user_id', 'message', 'like_total_count', 'avatar_image_url', 'users.name as user_name', 'posts.created_at as post_created_at'])
                     ->orderBy('posts.id', 'desc')
                     ->take(100)
                     ->get();

        return Inertia::render('Home', [
            'posts' => $posts,
        ]);
    }

    /**
     * 自分の投稿一覧画面アクション
     *
     * @return void
     */
    public function me()
    {
        // 最新の自分の投稿を100件取得
        $posts = Post::join('users', 'users.id', '=', 'posts.user_id')
                     ->select(['posts.id as post_id', 'user_id', 'message', 'like_total_count', 'avatar_image_url', 'users.name as user_name', 'posts.created_at as post_created_at'])
                     ->where('user_id', Auth::id())
                     ->orderBy('posts.id', 'desc')
                     ->take(100)
                     ->get();

        return Inertia::render('Home', [
            'posts' => $posts,
        ]);
    }
}
