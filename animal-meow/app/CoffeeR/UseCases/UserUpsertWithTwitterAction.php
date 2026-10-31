<?php

namespace App\CoffeeR\UseCases;
use App\Models\User;
use App\Models\TwitterUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Twitterを使ったユーザーの新規登録・更新
 */
class UserUpsertWithTwitterAction
{
    private $user;
    private $twitterUser;

    public function __construct(User $user, TwitterUser $twitterUser)
    {
        $this->user = $user;
        $this->twitterUser = $twitterUser;
    }

    public function __invoke(\Laravel\Socialite\Contracts\User $socialiteUser): User
    {
        // twitterのidを条件にユーザーを取得
        $user = $this->user
                     ->join('twitter_users', 'users.id', '=', 'twitter_users.animal_meow_user_id')
                     ->where('twitter_users.twitter_id', $socialiteUser->getId())
                     ->select('users.*')
                     ->first();

        $twitterUser = $this->twitterUser
                            ->where('twitter_id', $socialiteUser->getId())
                            ->first();

        // トランザクション
        DB::beginTransaction();

        // ユーザーが既に存在する場合は更新処理、存在しない場合は新規作成する
        // ユーザーが存在しない場合は、ユーザーとtokenレコードの新規作成を行う。
        if ($user) {
            // ユーザーの更新
            $user->name = $socialiteUser->getName();
            $user->avatar_image_url = str_replace( "_normal.", "_bigger.", $socialiteUser->getAvatar());
            $user->update();

            // Twitterユーザーの更新
            $twitterUser->nickname = $socialiteUser->getNickname();
            $twitterUser->access_token = $socialiteUser->token;
            $twitterUser->access_token_time_limit = new Carbon('+' . $socialiteUser->expiresIn . ' seconds');
            $twitterUser->refresh_token = $socialiteUser->refreshToken;
            $twitterUser->update();
        } else {
            // ユーザーの新規作成
            $user = new User();
            $user->name = $socialiteUser->getName();
            $user->avatar_image_url = str_replace( "_normal.", "_bigger.", $socialiteUser->getAvatar());
            $user->save();

            // Twitterユーザーの新規作成
            $twitterUser = new TwitterUser();
            $twitterUser->twitter_id = $socialiteUser->getId();
            $twitterUser->animal_meow_user_id = $user->id;
            $twitterUser->nickname = $socialiteUser->getNickname();
            $twitterUser->access_token = $socialiteUser->token;
            $twitterUser->access_token_time_limit = new Carbon('+' . $socialiteUser->expiresIn . ' seconds');
            $twitterUser->refresh_token = $socialiteUser->refreshToken;
            $twitterUser->save();
        }

        // コミット
        DB::commit();

        return $user;
    }
}