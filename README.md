Overview
========

This package implements a collection of objects of same type that have a unique field defined
as a key. It requires PHP 7.2+ to run.

Usage
=====

First, create a data class that implements `VKR\Collection\Contracts\Collectable` and define
its `getKey()` method. It is important that `getKey()` should return the value of the key field,
not its name, so it should look like this:
```
public function getKey(): string
{
    return $this->keyField;
}
```

It is up to client coders to ensure that the value of the key field is always string and
always unique.

Then, initialize `VKR\Collection\Collection` and add your data objects to it using `add(Collectable $object)`.
All elements of a single collection object MUST be of same type. If an object with the same
key exists in the collection, `add()` will overwrite it.

`remove(string $key)` will delete an object from the collection by key and return the removed object. If no
element with the given key is found, it will return `null`.

`get(string $key)` will retrieve objects by key, or `null` if not found.

`all()` retrieves zero-indexed array of collection member objects. If an object was removed
from the collection, keys get re-indexed to the natural order of elements.

`toScalarArray(string $valueField)` converts the collection into one-dimensional associative array
with values of the key field as keys and values of `$valueField` as values.

`toArray()` converts the collection into an associative array that is identical to the
result of `all()`, but numeric keys are swapped for values of the key field.

If the data class implements `VKR\Collection\Contracts\Arrayable`, `toArray()` will attempt
deep rendering by calling the `toArray()` method on each data object.
