<?php

namespace Stoatally\Dom\NodeTypes;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface Iterator extends ArrayAccess, Countable, IteratorAggregate, Node, ImportableNode, ChildIterator, QueryableNode
{
    /**
     * Fill each node with values from a set of items.
     */
    public function fillNodes($items, ?Callable $callback = null): Iterator;
}