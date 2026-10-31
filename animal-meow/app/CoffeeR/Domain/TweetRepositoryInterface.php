<?php

namespace App\CoffeeR\Domain;

interface TweetRepositoryInterface
{
    public function tweet(string $message): Tweet;
}