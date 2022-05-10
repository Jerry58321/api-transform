<?php


namespace Goodgod\ApiTransform\Exceptions;

use Throwable;

class OnlyOneFalseKey extends \LogicException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}