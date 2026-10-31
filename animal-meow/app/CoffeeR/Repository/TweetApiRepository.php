<?php

namespace App\CoffeeR\Repository;

use App\CoffeeR\Domain\Tweet;
use App\CoffeeR\Domain\TweetRepositoryInterface;
use App\Exceptions\TwitterClientException;
use Illuminate\Support\Facades\Auth;
use App\Models\TwitterUser;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TweetApiRepository implements TweetRepositoryInterface
{
    private const ENDPOINT_URL = 'https://api.twitter.com/2/tweets';

    private $twitterUser;

    public function __construct(TwitterUser $twitterUser)
    {
        $this->twitterUser = $twitterUser;
    }

    public function tweet(string $message): Tweet
    {
        // Twitterのユーザー情報を取得する
        $twitterUser = $this->twitterUser->where('animal_meow_user_id', Auth::id())->first();

        // ツイートする
        $response = Http::withToken($twitterUser->access_token)
                             ->post(self::ENDPOINT_URL, [
                                 'text' => $message,
                             ]);
        
        // Twitter側でエラーが起きている場合はどうしようもないのでログとって例外なげる
        if($response->serverError()){
            Log::error('user_id='.Auth::id()." ".$response->body());
            throw new Exception($response->body());
        }
        
        // クライアント側に問題があるエラーは、
        // エンドユーザー向けに何が起きたかを説明するためのメッセージを例外に入れて投げる
        if($response->clientError()){

            Log::error('user_id='.Auth::id()." ".$response->body());

            // 繰り返し同じ内容を投稿しようとした時のエラー
            if($response->object()->detail == 'You are not allowed to create a Tweet with duplicate content.'){
                throw new TwitterClientException('同一の文章を複数回投稿しようとしたためツイート出来ませんでした。別の文章で投稿をお試しください。');
            }

            // 認可エラー
            if($response->status() === 401){
                throw new TwitterClientException('Twitterとの連携に問題が起きたためツイート出来ませんでした。恐れ入りますが当サイトから一度ログアウトした後、投稿をお試しください。');
            }

            // その他のクライアントエラー
            throw new TwitterClientException('不明なエラーが発生したため、ツイート出来ませんでした。');
        }

        // レスポンスを配列に加工する
        $body = $response->object();

        return new Tweet(
            $body->data->text,
            'https://twitter.com/'.$twitterUser->nickname.'/status/'.$body->data->id
        );
    }
}
