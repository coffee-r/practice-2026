<?php

namespace App\CoffeeR\UseCases;

use App\CoffeeR\Domain\Tweet;
use App\CoffeeR\Domain\TweetRepositoryInterface;
use App\CoffeeR\Domain\TwitterTokenRepositoryInterface;
use Carbon\Carbon;
use App\Models\TwitterUser;
use Illuminate\Support\Facades\Auth;

/**
 * ツイート
 */
class TweetAction
{   
    private $twitterUser;
    private $tweetRepository;
    private $twitterTokenRepository;

    public function __construct(TwitterUser $twitterUser, TweetRepositoryInterface $tweetRepository, TwitterTokenRepositoryInterface $twitterTokenRepository)
    {
        $this->twitterUser = $twitterUser;
        $this->tweetRepository = $tweetRepository;
        $this->twitterTokenRepository = $twitterTokenRepository;
    }

    public function __invoke(string $message): Tweet
    {
        // twitterユーザー情報を取得
        $twitterUser = $this->twitterUser->where('animal_meow_user_id', Auth::id())->first();

        // 現在時刻を取得
        $now = Carbon::now();

        // アクセストークンの有効期限を取得
        // (有効期限の判定に余裕を持たせるため一旦10秒前を取得)
        $limit = (new Carbon($twitterUser->access_token_time_limit))->subSecond(10);

        // アクセストークンの有効期限が切れている場合、または切れそうな場合には、
        // アクセストークンを再発行して更新する
        if ($now->gte($limit)) {
            $this->twitterTokenRepository->refreshToken();
        }

        // ツイートのタグ等を含めた文章を加工
        $messageWithTag = $message . "\r\n" . '#' . config('app.name') . "\r\n" . config('app.url');

        // ツイートする
        return $this->tweetRepository->tweet($messageWithTag);
    }
}