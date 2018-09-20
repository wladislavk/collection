<?php

namespace VKR\Collection\Tests;

use VKR\Collection\Contracts\Arrayable;
use VKR\Collection\Contracts\Collectable;

class ArrayableDummy implements Collectable, Arrayable
{
    /** @var string */
    public $name = '';

    /** @var string */
    public $value = '';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->name, $this->value];
    }
}
