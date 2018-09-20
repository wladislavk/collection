<?php

namespace VKR\Collection;

use VKR\Collection\Contracts\Arrayable;
use VKR\Collection\Contracts\Collectable;
use VKR\Collection\Exceptions\PropertyNotFoundException;
use VKR\Collection\Exceptions\TypeMismatchException;

class Collection
{
    /** @var Collectable[] */
    private $elements = [];

    /**
     * @return Collectable[]
     */
    public function all(): array
    {
        return $this->elements;
    }

    public function get(string $key): ?Collectable
    {
        foreach ($this->elements as $numericKey => $element) {
            if ($element->getKey() === $key) {
                return $element;
            }
        }
        return null;
    }

    /**
     * @param Collectable $newElement
     * @throws TypeMismatchException
     */
    public function add(Collectable $newElement): void
    {
        if (isset($this->elements[0]) && get_class($this->elements[0]) !== get_class($newElement)) {
            throw new TypeMismatchException($newElement, $this->elements[0]);
        }
        foreach ($this->elements as $numericKey => $element) {
            if ($element->getKey() == $newElement->getKey()) {
                $this->elements[$numericKey] = $newElement;
                return;
            }
        }
        $this->elements[] = $newElement;
    }

    /**
     * @param string $key
     * @return null|Collectable
     */
    public function remove(string $key): ?Collectable
    {
        foreach ($this->elements as $numericKey => $element) {
            if ($element->getKey() === $key) {
                unset($this->elements[$numericKey]);
                $this->elements = array_values($this->elements);
                return $element;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->elements as $element) {
            if ($element instanceof Arrayable) {
                $array[$element->getKey()] = $element->toArray();
            } else {
                $array[$element->getKey()] = $element;
            }
        }
        return $array;
    }

    /**
     * @param string $valueColumn
     * @return array
     * @throws PropertyNotFoundException
     */
    public function toScalarArray(string $valueColumn): array
    {
        $array = [];
        foreach ($this->elements as $element) {
            if (!property_exists($element, $valueColumn)) {
                throw new PropertyNotFoundException($valueColumn, $element);
            }
            $array[$element->getKey()] = $element->$valueColumn;
        }
        return $array;
    }
}
