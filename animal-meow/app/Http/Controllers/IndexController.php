<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    /**
     * トップページアクション
     *
     * @return void
     */
    public function index()
    {
        // 最新の投稿10件をキャッシュしつつ取得
        $posts = Cache::remember('index_posts', 60, function(){
            return Post::join('users', 'users.id', '=', 'posts.user_id')
                        ->select(['posts.id as post_id', 'user_id', 'message', 'like_total_count', 'avatar_image_url', 'users.name as user_name', 'posts.created_at as post_created_at'])
                        ->orderBy('posts.id', 'desc')
                        ->take(10)
                        ->get();
        });

        return Inertia::render('Index', [
            'posts' => $posts,
        ]);
    }
}
