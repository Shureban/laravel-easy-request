<?php

namespace Shureban\LaravelEasyRequest\Exceptions;

use Throwable;

class UndefinedClassException extends EasyRequestException
{
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Undefined class %s', $class);

        parent::__construct($message, $code, $previous);
    }
}
