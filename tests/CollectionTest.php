<?php

namespace VKR\Collection\Tests;

use PHPUnit\Framework\TestCase;
use VKR\Collection\Collection;
use VKR\Collection\Exceptions\PropertyNotFoundException;
use VKR\Collection\Exceptions\TypeMismatchException;

class CollectionTest extends TestCase
{
    /** @var Collection */
    private $collection;

    /**
     * @throws \VKR\Collection\Exceptions\TypeMismatchException
     */
    public function setUp()
    {
        $this->collection = new Collection();

        $firstDummy = new SimpleDummy();
        $firstDummy->name = 'first_name';
        $firstDummy->value = 'first_value';
        $this->collection->add($firstDummy);

        $secondDummy = new SimpleDummy();
        $secondDummy->name = 'second_name';
        $secondDummy->value = 'second_value';
        $this->collection->add($secondDummy);
    }

    /**
     * @throws \VKR\Collection\Exceptions\TypeMismatchException
     */
    public function testAddNew()
    {
        $thirdDummy = new SimpleDummy();
        $thirdDummy->name = 'third_name';
        $thirdDummy->value = 'third_value';
        $this->collection->add($thirdDummy);

        /** @var SimpleDummy[] $elements */
        $elements = $this->collection->all();
        $this->assertEquals(3, sizeof($elements));
        $this->assertEquals('third_value', $elements[2]->value);
    }

    /**
     * @throws \VKR\Collection\Exceptions\TypeMismatchException
     */
    public function testAddExisting()
    {
        $thirdDummy = new SimpleDummy();
        $thirdDummy->name = 'second_name';
        $thirdDummy->value = 'third_value';
        $this->collection->add($thirdDummy);

        /** @var SimpleDummy[] $elements */
        $elements = $this->collection->all();
        $this->assertEquals(2, sizeof($elements));
        $this->assertEquals('third_value', $elements[1]->value);
    }

    /**
     * @throws TypeMismatchException
     */
    public function testAddObjectOfDifferentType()
    {
        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessage("Attempt to add object of class " . ArrayableDummy::class . " to collection of objects of class " . SimpleDummy::class);

        $thirdDummy = new ArrayableDummy();
        $thirdDummy->name = 'third_name';
        $thirdDummy->value = 'third_value';
        $this->collection->add($thirdDummy);
    }

    public function testRemoveExisting()
    {
        /** @var SimpleDummy $removed */
        $removed = $this->collection->remove('first_name');

        $this->assertEquals('first_value', $removed->value);
        /** @var SimpleDummy[] $elements */
        $elements = $this->collection->all();
        $this->assertEquals(1, sizeof($elements));
        $this->assertEquals('second_value', $elements[0]->value);
    }

    public function testRemoveNonExisting()
    {
        $removed = $this->collection->remove('foo');

        $this->assertNull($removed);
        /** @var SimpleDummy[] $elements */
        $elements = $this->collection->all();
        $this->assertEquals(2, sizeof($elements));
    }

    public function testGetByKeyExisting()
    {
        /** @var SimpleDummy $retrieved */
        $retrieved = $this->collection->get('first_name');
        $this->assertEquals('first_value', $retrieved->value);
    }

    public function testGetByKeyNonExisting()
    {
        $retrieved = $this->collection->get('foo');
        $this->assertNull($retrieved);
    }

    public function testToArraySimple()
    {
        $array = $this->collection->toArray();

        $this->assertEquals(['first_name', 'second_name'], array_keys($array));
        $this->assertEquals('second_value', $array['second_name']->value);
    }

    /**
     * @throws TypeMismatchException
     */
    public function testToArrayArrayable()
    {
        $collection = new Collection();

        $firstDummy = new ArrayableDummy();
        $firstDummy->name = 'first_name';
        $firstDummy->value = 'first_value';
        $collection->add($firstDummy);

        $secondDummy = new ArrayableDummy();
        $secondDummy->name = 'second_name';
        $secondDummy->value = 'second_value';
        $collection->add($secondDummy);

        $array = $collection->toArray();

        $expected = [
            'first_name' => ['first_name', 'first_value'],
            'second_name' => ['second_name', 'second_value'],
        ];
        $this->assertEquals($expected, $array);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function testToScalarArray()
    {
        $array = $this->collection->toScalarArray('value');

        $expected = ['first_name' => 'first_value', 'second_name' => 'second_value'];
        $this->assertEquals($expected, $array);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function testToScalarArrayWithBadArgument()
    {
        $this->expectException(PropertyNotFoundException::class);
        $this->expectExceptionMessage("Property foo does not exist in class " . SimpleDummy::class);

        $this->collection->toScalarArray('foo');
    }
}
