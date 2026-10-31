<?php

namespace Tests\Unit\CoffeeR\Domain;

use App\CoffeeR\Domain\TwitterToken;
use Tests\TestCase;

class TwitterTokenTest extends TestCase
{
    public function test_blank_access_token()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('アクセストークンが空文字です。');
        $tweet = new TwitterToken('', new \DateTime(), 'test_refresh_token');
    }

    public function test_blank_refresh_token()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('リフレッシュトークンが空文字です。');
        $tweet = new TwitterToken('test_access_token', new \DateTime(), '');
    }
}