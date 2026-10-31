<?php

namespace App\Http\Controllers;

use App\CoffeeR\UseCases\PostDeleteAction;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Animal;
use App\CoffeeR\UseCases\PostStoreAction;
use App\CoffeeR\UseCases\TweetAction;
use App\Exceptions\PostStoreException;
use App\Exceptions\TwitterClientException;
use Exception;

class PostController extends Controller
{
    /**
     * 新規投稿画面
     *
     * @return void
     */
    public function create()
    {
        // 投稿に利用できる言葉を動物ごとに取得する
        $animals = Animal::all();

        if($animals->isEmpty()){
            throw new Exception();
        }

        return Inertia::render('Post', [
            'animals' => $animals,
        ]);
    }

    /**
     * 投稿アクション
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request, PostStoreAction $postStoreAction, TweetAction $tweetAction)
    {
        // フォームバリデーション
        $validated = $request->validate([
            'animalId' => 'required|integer',
            'message' => 'required|max:100',
        ],[
            'animalId.required' => '動物を選択してください',
            'animalId.integer' => '動物を正しく選択してください',
            'message.required' => '投稿メッセージを入力してください',
            'message.max' => '投稿メッセージは最大100文字までです',
        ]);

        // フラッシュメッセージ用変数
        $successMessages = [];
        

        // トランザクション
        // このサービスの投稿もツイートも成功 ⇨ コミット
        // このサービスの投稿が失敗 ⇨ ロールバック
        // このサービスの投稿は成功してツイートが失敗 ⇨ ロールバック
        DB::beginTransaction();

        try{
            // 新規投稿処理
            $postStoreAction($request->input('animalId'), $request->input('message'));
            $successMessages[] = "投稿しました。";

            // ツイート
            // TwitterAPI有料化のため廃止
            // if ($request->input('withTweet')) {
            //     $tweet = $tweetAction($request->input('message'));
            //     $successMessages[] = "<a class='underline' href='".$tweet->url."' target='_blank'>Twitter</a>に投稿しました。";
            // }
        }catch(PostStoreException $e){
            // エラーログ記録
            Log::error($e);

            // フラッシュメッセージ設定
            $failMessages = [];
            $failMessages[] = "投稿に失敗しました。";
            $failMessages[] = $e->getMessage();
            
            // ホーム画面にリダイレクト
            return redirect(route('home'))->with('failMessages', $failMessages);
        }

        // コミット
        DB::commit();

        // ホーム画面にリダイレクト
        return redirect(route('home'))->with('successMessages', $successMessages);
    }

    /**
     * 投稿削除
     *
     * @param [type] $id posts.id
     * @return void
     */
    public function destroy(int $post_id, PostDeleteAction $postDeleteAction)
    {
        // 投稿削除
        $postDeleteAction($post_id);

        // ホーム画面にリダイレクト
        return redirect(url()->previous())->with('successMessages', ['投稿を削除しました。']);
    }
}
