<?php

namespace Stoatally\Dom\NodeTypes;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface Iterator extends ArrayAccess, Countable, IteratorAggregate, Node, ImportableNode, ChildIterator, QueryableNode
{
    public function fillNodes($items, ?Callable $callback = null): Iterator;
}