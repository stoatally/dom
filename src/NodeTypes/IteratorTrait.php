<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;
use LogicException;
use OutOfBoundsException;

trait IteratorTrait
{
    public function getImportableNode(): Node
    {
        $document = $this->getDocument();
        $fragment = $document->createDocumentFragment();

        foreach ($this as $node) {
            $fragment->appendChild(clone $node);
        }

        return $fragment;
    }

    public function getChildren(): Iterator
    {
        return $this;
    }

    public function getNode(): Node
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

    public function importNode($value): Node
    {
        return $this->getDocument()->importNode($value);
    }

    public function setContent($value): Node
    {
        try {
            return $this[0]->setContent($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function getContent(): ?string
    {
        try {
            return $this[0]->getContent();
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function setRawContent(string $xmlOrHtml): Node
    {
        try {
            return $this[0]->setRawContent($xmlOrHtml);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function getRawContent(): string
    {
        try {
            return $this[0]->getRawContent();
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function appendChild($value): Node
    {
        try {
            return $this[0]->appendChild($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function prependChild($value): Node
    {
        try {
            return $this[0]->prependChild($value);
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
                    $node->setContent($items[$index]);
                }
            }

            $index++;
        }

        return $this;
    }
}