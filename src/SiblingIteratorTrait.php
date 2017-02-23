<?php

namespace Stoatally\Dom;

use DomNode;
use OutOfBoundsException;

trait SiblingIteratorTrait
{
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

    public function replace($value): DomNode
    {
        try {
            return $this[0]->replace($value);
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function wrap($value): DomNode
    {
        try {
            $parent = $this[0]->before($value);

            foreach ($this->nodes as $child) {
                $parent->appendChild($child);
            }

            return $parent;
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }
}