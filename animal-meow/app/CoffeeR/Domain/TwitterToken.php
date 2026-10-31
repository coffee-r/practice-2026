<?php

namespace App\CoffeeR\Domain;

use DateTime;
use InvalidArgumentException;

class TwitterToken{
    public readonly string $accessToken;
    public readonly DateTime $accessTokenExpireDate;
    public readonly string $refreshToken;
    
    public function __construct(string $accessToken, DateTime $accessTokenExpireDate, string $refreshToken)
    {
        if(mb_strlen($accessToken) == 0){
            throw new InvalidArgumentException('アクセストークンが空文字です。');
        }

        if(mb_strlen($refreshToken) == 0){
            throw new InvalidArgumentException('リフレッシュトークンが空文字です。');
        }

        $this->accessToken = $accessToken;
        $this->accessTokenExpireDate = $accessTokenExpireDate;
        $this->refreshToken = $refreshToken;
    }
}