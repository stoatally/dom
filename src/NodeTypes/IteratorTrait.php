<?php

namespace Stoatally\Dom\NodeTypes;

use DomDocument;
use DomNode;
use LogicException;
use OutOfBoundsException;

trait IteratorTrait
{
    public function getImportableNode(): DomNode
    {
        $document = $this->getDocument();
        $fragment = $document->createDocumentFragment();

        foreach ($this as $node) {
            $fragment->appendChild($node->cloneNode(true));
        }

        return $fragment;
    }

    public function getNode(): DomNode
    {
        try {
            return $this[0]->getNode();
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    private function createEmptyIteratorException(string $method)
    {
        return new LogicException($method . ' called on an empty iterator.');
    }

    public function import($value): Node
    {
        return $this->getDocument()->import($value);
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

    public function duplicate(int $times): Iterator
    {
        try {
            return $this[0]->duplicate($times);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function repeat($items, ?Callable $callback = null): Iterator
    {
        try {
            return $this[0]->repeat($items, $callback);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function select(string $query): Iterator
    {
        try {
            return $this[0]->select($query);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function fill($items, ?Callable $callback = null): Iterator
    {
        $index = 0;

        foreach ($this as $node) {
            if (isset($items[$index])) {
                if (isset($callback)) {
                    $callback($node, $items[$index]);
                }

                else {
                    $node->nodeValue = null;
                    $node->set($items[$index]);
                }
            }

            $index++;
        }

        return $this;
    }
}