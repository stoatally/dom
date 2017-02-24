<?php

namespace Stoatally\Dom\NodeTypes;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface Iterator extends ArrayAccess, Countable, IteratorAggregate, ChildIterator, Node, ImportableNode, ParentNode, QueryableNode
{
    public function fill($items, ?Callable $callback = null): Iterator;
}