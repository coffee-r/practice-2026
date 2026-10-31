<?php

namespace App\Packages\User\Domain;

class UserEmailMagazineSubscription
{
    public readonly bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }
}