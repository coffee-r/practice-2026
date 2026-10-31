<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\UseCases\PostStoreAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        // 投稿を取得する
        $posts = Post::join('users', 'users.id', '=', 'posts.user_id')
                    ->select(['posts.id as post_id', 'user_id', 'message', 'users.name as user_name'])
                    ->orderBy('posts.id', 'desc')
                    ->get();
        
        // 投稿データをビューに渡す
        return Inertia::render('Posts', [
            'posts' => $posts,
        ]);
    }
    
    public function store(StorePostRequest $request, PostStoreAction $postStoreAction): RedirectResponse
    {
        // モデルクラスをインスタンス化 (バリデーションした値を同時に入れる)
        $post = new Post($request->validated());

        // ログインユーザーのユーザーIDをセット
        $post->user_id = Auth::user()->id;

        // データベースの値に応じたバリデーション
        // 及び永続化
        $postStoreAction(Auth::user()->id, $post);

        // フラッシュメッセージと共に一覧画面に遷移
        return back()->with('message', '投稿しました');
    }

    public function destroy(Post $post): RedirectResponse
    {
        // 認可 (先ほど作ったPolicyクラスが内部で動作する)
        $this->authorize('delete', $post);

        // 投稿を削除
        $post->delete();

        // フラッシュメッセージと共に一覧画面に遷移
        return back()->with('message', '投稿を削除しました');
    }
}
