<?php

namespace Stoatally\Dom\NodeTypes;

use OutOfBoundsException;
use Stoatally\Dom\Nodes;

trait ChildIteratorTrait
{
    public function getParent(): Node
    {
        try {
            return $this[0]->getParent();
        }

        catch (OutOfBoundsException $error) {
            throw $this->createEmptyIteratorException(__METHOD__);
        }
    }

    public function hasParent(): bool
    {
        try {
            return $this[0]->hasParent();
        }

        catch (OutOfBoundsException $error) {
            return false;
        }
    }

    public function after($value): ChildNode
    {
        $node = $this->import($value);
        $results = iterator_to_array($this);

        if (false === empty($results)) {
            $last = end($results);
            $last->after($node);
        }

        $results[] = $node;

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function before($value): ChildNode
    {
        $node = $this->import($value);
        $results = [$node];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $child->before($node);
            }

            $results[] = $child;
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    /**
     * Insert a node between each of the nodes in an iterator.
     *
     * @param   string|ChildNode|ImportableNode     $value
     *  The node to insert between the existing nodes.
     */
    public function between($value): ChildNode
    {
        $node = $this->import($value);
        $results = [];

        foreach ($this as $index => $child) {
            if ($index > 0) {
                $new = $node->cloneNode(true);
                $child->before($new);
                $results[] = $new;
            }

            $results[] = $child;
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function replace($value): ChildNode
    {
        $node = $this->import($value);
        $results = [$node];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $child->replace($node);
            }

            else if ($child->parentNode) {
                $child->parentNode->removeChild($child);
            }
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function wrap($value): ChildNode
    {
        $parent = $this->import($value);
        $results = [$parent];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $this[0]->before($parent);
            }

            $parent->appendChild($child);
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }
}