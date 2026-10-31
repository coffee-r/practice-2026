<?php

namespace Tests\Unit\CoffeeR\Domain;

use PHPUnit\Framework\TestCase;
use App\CoffeeR\Domain\Tweet;

class TweetTest extends TestCase
{
    public function test_blank_text()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ツイートのテキストが空文字です。');
        $tweet = new Tweet('', 'https://example.com');
    }

    public function test_non_url()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ツイートのURLがhttps://で始まっていません。');
        $tweet = new Tweet('テストメッセージ', 'example.com');
    }

    public function test_normal_tweet()
    {
        $tweet = new Tweet('テストメッセージ', 'https://example.com');
        $this->assertEquals('テストメッセージ', $tweet->text);
        $this->assertEquals('https://example.com', $tweet->url);
    }
}
