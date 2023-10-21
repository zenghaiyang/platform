<?php

namespace Platform\exception;

use Exception;


/**
 * 中台异常
 */
class PlatformException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}