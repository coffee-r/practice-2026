<?php

namespace App\Packages\Shared\Infrastructure;

use Exception;
use Throwable;

class InfrastructureException extends Exception
{
    public function __construct($message, $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
