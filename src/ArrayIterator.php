<?php

namespace Stoatally\Dom;

use ArrayIterator as NativeArrayIterator;
use IteratorAggregate;
use LogicException;
use OutOfBoundsException;

class ArrayIterator implements Iterator, IteratorAggregate
{
    use IteratorTrait;
    use SiblingIteratorTrait;

    private $nodes;

    public function __construct(array $nodes)
    {
        $this->nodes = new NativeArrayIterator($nodes);
    }

    public function getIterator()
    {
        return $this->nodes;
    }

    public function count(): int
    {
        return $this->nodes->count();
    }

    public function current()
    {
        return $this->nodes->current();
    }

    public function next()
    {
        $this->nodes->next();
    }

    public function rewind()
    {
        $this->nodes->rewind();
    }

    public function offsetExists($offset)
    {
        $offset = $this->prepareOffset($offset);

        return $offset >= 0 && $offset < $this->nodes->count();
    }

    public function offsetGet($offset)
    {
        if (false === isset($this[$offset])) {
            throw new OutOfBoundsException(sprintf(
                'Offset %d is out of bounds.',
                $offset
            ));
        }

        return $this->nodes[$this->prepareOffset($offset)];
    }

    public function offsetSet($offset, $value)
    {
        throw new LogicException('NodeListIterator is immutable.');
    }

    public function offsetUnset($offset)
    {
        throw new LogicException('NodeListIterator is immutable.');
    }

    private function prepareOffset(int $offset)
    {
        if ($offset < 0) {
            return $this->nodes->count() + $offset;
        }

        return $offset;
    }
}