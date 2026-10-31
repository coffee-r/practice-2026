<?php

namespace App\Packages\Shared\Usecase;

use Exception;
use Throwable;

class UsecaseException extends Exception
{
    public function __construct($message, $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
