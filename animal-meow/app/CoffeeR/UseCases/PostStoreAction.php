<?php

namespace App\CoffeeR\UseCases;

use App\Exceptions\PostStoreException;
use App\Models\Post;
use App\Models\Animal;
use Illuminate\Support\Facades\Auth;

/**
 * 新規投稿
 */
class PostStoreAction
{
    private $animal;

    public function __construct(Post $post, Animal $animal)
    {
        $this->animal = $animal;
    }

    public function __invoke(int $animalId, string $message): Post
    {
        // 動物マスタを取得
        $animals = $this->animal->find($animalId);

        // 入力された動物idに対応するマスタが見つからなかったらエラー
        if(!$animals){
            throw new PostStoreException('動物id「' . $animalId . '」に対応する動物が存在しません。');
        }

        // ブランク文字だけの投稿メッセージは許容しない
        if (mb_strlen($message) === 0) {
            throw new PostStoreException('空文字だけの投稿はできません。');
        }

        // 空白文字だけの投稿メッセージは許容しない
        if (ctype_space($message) === true) {
            throw new PostStoreException('空白文字だけでの投稿はできません。');
        }

        // 100文字を超える投稿メッセージは許容しない
        if (mb_strlen($message) > 100) {
            throw new PostStoreException('投稿メッセージは100文字までです。');
        }

        // 投稿メッセージを1文字ずつに分割
        $splited_message = mb_str_split($message);

        // 投稿メッセージを1文字ずつ検証していく
        foreach ($splited_message as $key => $word) {

            // 半角スペースは許容する
            if($word === ' '){
                continue;
            }

            // 動物マスタの言語文字以外の文字は許容しない
            if (in_array($word, $animals->words, true) === false) {
                throw new PostStoreException('投稿メッセージ内の「'.$word.'」は使用することができない文字です。');
            }
        }
 
         // 新規投稿
         $post = new Post();
         $post->user_id = Auth::id();
         $post->animal_id = $animalId;
         $post->message = $message;
         $post->save();

         return $post;
    }
}