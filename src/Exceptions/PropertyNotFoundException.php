<?php

namespace VKR\Collection\Exceptions;

use Throwable;

class PropertyNotFoundException extends \Exception
{
    /**
     * PropertyNotFoundException constructor.
     * @param string $property
     * @param object $element
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $property, object $element, int $code = 0, Throwable $previous = null)
    {
        $message = "Property $property does not exist in class " . get_class($element);
        parent::__construct($message, $code, $previous);
    }
}
