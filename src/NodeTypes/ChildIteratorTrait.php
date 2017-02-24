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

    public function appendSibling($value): ChildNode
    {
        $node = $this->importNode($value);
        $results = iterator_to_array($this);

        if (false === empty($results)) {
            $last = end($results);
            $last->appendSibling($node);
        }

        $results[] = $node;

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function prependSibling($value): ChildNode
    {
        $node = $this->importNode($value);
        $results = [$node];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $child->prependSibling($node);
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
        $node = $this->importNode($value);
        $results = [];

        foreach ($this as $index => $child) {
            if ($index > 0) {
                $new = clone $node;
                $child->prependSibling($new);
                $results[] = $new;
            }

            $results[] = $child;
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function remove(): ChildNode
    {
        foreach ($this as $child) {
            $child->remove();
        }

        return $this;
    }

    public function replaceWith($value): ChildNode
    {
        $node = $this->importNode($value);
        $results = [$node];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $child->replaceWith($node);
            }

            else if ($child->hasParent()) {
                $child->getParent()->getLibxml()->removeChild($child->getLibxml());
            }
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function wrapWith($value): ChildNode
    {
        $parent = $this->importNode($value);
        $results = [$parent];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $this[0]->prependSibling($parent);
            }

            $parent->appendChild($child);
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }
}