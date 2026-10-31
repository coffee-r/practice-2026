<?php

namespace App\CoffeeR\Domain;

interface TwitterTokenRepositoryInterface
{
    public function refreshToken(): void;
}