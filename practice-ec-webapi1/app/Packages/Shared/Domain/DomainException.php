<?php

namespace App\Packages\Shared\Domain;

use Exception;
use Throwable;

class DomainException extends Exception
{
    public function __construct($message, $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
