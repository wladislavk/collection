<?php

namespace VKR\Collection\Exceptions;

use Throwable;

class TypeMismatchException extends \Exception
{
    /**
     * TypeMismatchException constructor.
     * @param object $newObject
     * @param object $existingObject
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(object $newObject, object $existingObject, int $code = 0, Throwable $previous = null)
    {
        $message = "Attempt to add object of class " . get_class($newObject) . " to collection of objects of class " . get_class($existingObject);
        parent::__construct($message, $code, $previous);
    }
}
