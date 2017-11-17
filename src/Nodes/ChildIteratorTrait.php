<?php

namespace Stoatally\Dom\Nodes;

use OutOfBoundsException;
use Stoatally\Dom\NodeTypes;

trait ChildIteratorTrait
{
    public function appendSibling($value): NodeTypes\ChildNode
    {
        $node = $this->import($value);
        $results = iterator_to_array($this);

        if (false === empty($results)) {
            $last = end($results);
            $last->appendSibling($node);
        }

        $results[] = $node;

        return new Iterator($this->getDocument(), $results);
    }

    public function prependSibling($value): NodeTypes\ChildNode
    {
        $node = $this->import($value);
        $results = [$node];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $child->prependSibling($node);
            }

            $results[] = $child;
        }

        return new Iterator($this->getDocument(), $results);
    }

    /**
     * Insert a node between each of the nodes in an iterator.
     *
     * @param   string|ChildNode|ImportableNode     $value
     *  The node to insert between the existing nodes.
     */
    public function betweenSiblings($value): NodeTypes\ChildNode
    {
        $node = $this->import($value);
        $results = [];

        foreach ($this as $index => $child) {
            if ($index > 0) {
                $new = $node->cloneNode(true);
                $child->prependSibling($new);
                $results[] = $new;
            }

            $results[] = $child;
        }

        return new Iterator($this->getDocument(), $results);
    }

    public function replaceNode($value): NodeTypes\ChildNode
    {
        $node = $this->import($value);
        $results = [$node];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $child->replaceNode($node);
            }

            else if ($child->parentNode) {
                $child->parentNode->removeChild($child);
            }
        }

        return new Iterator($this->getDocument(), $results);
    }

    public function wrapNode($value): NodeTypes\ChildNode
    {
        $parent = $this->import($value);
        $results = [$parent];

        foreach ($this as $index => $child) {
            if ($index === 0) {
                $this[0]->prependSibling($parent);
            }

            $parent->append($child);
        }

        return new Iterator($this->getDocument(), $results);
    }
}