<?php

namespace VKR\Collection\Tests;

use VKR\Collection\Contracts\Collectable;

class SimpleDummy implements Collectable
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
}
