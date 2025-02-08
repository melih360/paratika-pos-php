<?php

namespace melih360\ParatikaPosPhp\Exceptions;

use Exception;
use Throwable;

class RequestErrorException extends Exception
{
    public function __construct($message = 'Request Error!', $code = 99, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}