<?php

namespace Stoatally\Dom;

use ArrayAccess;
use Countable;

interface Iterator extends ArrayAccess, Countable, Node, ImportableNode, SiblingIterator, QueryableNode
{
    public function fill($items, ?Callable $callback = null): Iterator;
}