<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\PostDeleteAction;
use App\Models\Posts;

class Post extends BaseController
{
    public function __construct()
    {
        helper('form');
    }
    
    public function index()
    {
        // model()ヘルパーを使ってモデルクラスを読み込む
        $postModel = model(Posts::class);

        // 投稿を取得する
        $posts = $postModel->asObject()->findAll();

        // ビューを表示
        return view('post_view', ['posts' => $posts]);
    }

    public function create()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'message'  => $this->request->getPost('message')
        ];

        $rules = [
            'message' => 'required|max_length[30]'
        ];

        if ($this->request->is('post') && $this->validateData($data, $rules) == false) {
            // model()ヘルパーを使ってモデルクラスを読み込む
            $postModel = model(Posts::class);

            // 投稿を取得する
            $posts = $postModel->asObject()->findAll();

            // ビューを表示
            return view('post_view', ['posts' => $posts]);
        }

        // model()ヘルパーを使ってモデルクラスを読み込む
        $postModel = model(Posts::class);

        // 現在の投稿数を取得
        $countPosts = $postModel->where('user_id', auth()->user()->id)->countAllResults();

        // 6つ以上は投稿できない
        if ($countPosts >= 5) {
            return redirect()->back()->with('error', '投稿は1ユーザーにつき最大5件までです。');
        }

        // 投稿を保存
        $postModel->save($data);

        return redirect()->back();
    }

    public function destroy(int $postId)
    {
        // model()ヘルパーを使ってモデルクラスを読み込む
        $postModel = model(Posts::class);

        // 削除ライブラリをインスタンス化
        $postDeleteAction = new PostDeleteAction();

        // 投稿を削除
        $postDeleteAction($postModel, $postId, auth()->user()->id);

        return redirect()->back();
    }
}