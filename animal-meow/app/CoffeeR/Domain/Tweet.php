<?php

namespace App\CoffeeR\Domain;

use InvalidArgumentException;

class Tweet{
    public readonly String $text;
    public readonly String $url;

    public function __construct(string $text, string $url) {

        if(mb_strlen($text) == 0){
            throw new InvalidArgumentException('ツイートのテキストが空文字です。');
        }

        if(strpos($url, 'https://') === false){
            throw new InvalidArgumentException('ツイートのURLがhttps://で始まっていません。');   
        }

        $this->text = $text;
        $this->url = $url;
    }
}