<?php

namespace App\CoffeeR\Repository;

use Carbon\Carbon;
use App\CoffeeR\Domain\TwitterTokenRepositoryInterface;
use App\Models\TwitterUser;
use Exception;
use App\Exceptions\TwitterClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TwitterTokenApiRepository implements TwitterTokenRepositoryInterface
{
    private const ENDPOINT_URL = 'https://api.twitter.com/2/oauth2/token';

    private $twitterUser;

    public function __construct(TwitterUser $twitterUser)
    {
        $this->twitterUser = $twitterUser;
    }

    public function refreshToken(): void
    {
        // Twitterのユーザー情報を取得する
        $twitterUser = $this->twitterUser->where('animal_meow_user_id', Auth::id())->first();

        // リフレッシュトークンを使い、アクセストークンを再発行する
        $response = Http::withBasicAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'))
                        ->post(self::ENDPOINT_URL, [
                            'refresh_token' => $twitterUser->refresh_token,
                            'grant_type' => 'refresh_token',
                            'client_id' => config('services.twitter.client_id'),
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

            // 認可エラー
            if($response->status() === 401){
                throw new TwitterClientException('Twitterとの連携に問題が発生しました。恐れ入りますが当サイトから一度ログアウトした後、投稿をお試しください。');
            }

            // その他のクライアントエラー
            throw new TwitterClientException('不明なエラーが発生しました。');
        }
        
        $body = $response->object();
        
        // 新しいアクセストークンを保存する
        $twitterUser->access_token = $body->access_token;
        $twitterUser->access_token_time_limit = new Carbon('+' . $body->expires_in . ' seconds');
        $twitterUser->refresh_token = $body->refresh_token;
        $twitterUser->update();
    }   
}
