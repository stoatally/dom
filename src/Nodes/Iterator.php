<?php

namespace Stoatally\Dom\Nodes;

use ArrayIterator;
use DomDocument;
use LogicException;
use OutOfBoundsException;
use Stoatally\Dom\NodeTypes;

class Iterator implements NodeTypes\Iterator
{
    use NodeTypes\IteratorTrait;
    use NodeTypes\ChildIteratorTrait;

    protected $document;
    protected $nodes;

    public function __construct(NodeTypes\Document $document, array $nodes)
    {
        $this->document = $document;
        $this->nodes = new ArrayIterator($nodes);
    }

    public function getDocument(): DomDocument
    {
        return $this->document;
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