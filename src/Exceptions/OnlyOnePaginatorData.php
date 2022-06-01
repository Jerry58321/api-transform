<?php


namespace jerry58321\ApiTransform\Exceptions;


use Throwable;

class OnlyOnePaginatorData extends \LogicException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}