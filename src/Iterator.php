<?php

namespace Stoatally\DocumentObjectModel;

use ArrayAccess;
use Countable;
use DomDocument;
use DomNode;
use DomNodeList;
use IteratorAggregate;
use LogicException;
use OutOfBoundsException;

class Iterator implements ArrayAccess, Countable, IteratorAggregate, Node, ImportableNode
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

    public function getImportableNode(): DomNode
    {
        $document = $this->getDocument();
        $fragment = $document->createDocumentFragment();

        foreach ($this as $node) {
            $fragment->appendChild($node->cloneNode(true));
        }

        return $fragment;
    }

    public function getDocument(): DomDocument
    {
        try {
            return $this[0]->getDocument();
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function import($value): DomNode
    {
        try {
            return $this[0]->import($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function set($value): DomNode
    {
        try {
            return $this[0]->set($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function get(): ?string
    {
        try {
            return $this[0]->get();
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function after($value): DomNode
    {
        try {
            return $this[-1]->after($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function before($value): DomNode
    {
        try {
            return $this[0]->before($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function append($value): DomNode
    {
        try {
            return $this[0]->append($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function prepend($value): DomNode
    {
        try {
            return $this[0]->prepend($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function replace($value): DomNode
    {
        try {
            return $this[0]->replace($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    private function createEmptyIteratorException(string $method)
    {
        return new LogicException($method . ' called on an empty iterator.');
    }








    // public function duplicate(int $times)
    // {
    //     if ($times < 2) {
    //         return $this;
    //     }

    //     $results = [];

    //     foreach ($this as $item) {
    //         $results[] = $item;

    //         foreach (range(1, $times - 1) as $index) {
    //             $clone = $results[] = $item->cloneNode(true);

    //             if ($item->parentNode) {
    //                 $item->insertAfter($clone);
    //                 $item = $clone;
    //             }
    //         }
    //     }

    //     return new Iterator(new ArrayIterator($results));
    // }

    // public function fill(array $children)
    // {
    //     foreach ($this as $index => $parent) {
    //         $parent->nodeValue = null;

    //         if (isset($children[$index])) {
    //             $parent->appendChild($parent->import($children[$index]));
    //         }
    //     }
    // }
}