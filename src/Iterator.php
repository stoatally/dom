<?php

namespace Stoatally\DocumentObjectModel;

use ArrayAccess;
use Countable;
use DomNodeList;
use IteratorAggregate;
use LogicException;
use OutOfBoundsException;

class Iterator implements ArrayAccess, Countable, IteratorAggregate
{
    private $nodes;

    public function __construct(DomNodeList $nodes)
    {
        $this->nodes = $nodes;
    }

    public function getIterator()
    {
        return $this->nodes;
    }

    public function count(): int
    {
        return $this->nodes->length;
    }

    public function offsetExists($offset)
    {
        $offset = $this->prepareOffset($offset);

        return $offset >= 0 && $offset < $this->nodes->length;
    }

    public function offsetGet($offset)
    {
        if (false === isset($this[$offset])) {
            throw new OutOfBoundsException(sprintf(
                'Offset %d is out of bounds.',
                $offset
            ));
        }

        return $this->nodes->item($this->prepareOffset($offset));
    }

    public function offsetSet($offset, $value)
    {
        throw new LogicException('Iterator is immutable.');
    }

    public function offsetUnset($offset)
    {
        throw new LogicException('Iterator is immutable.');
    }

    private function prepareOffset(int $offset)
    {
        if ($offset < 0) {
            return $this->nodes->length + $offset;
        }

        return $offset;
    }
}