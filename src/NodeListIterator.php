<?php

namespace Stoatally\Dom;

use DomNodeList;
use IteratorIterator;
use LogicException;
use OutOfBoundsException;

class NodeListIterator extends ArrayIterator
{
    public function __construct(DomNodeList $nodes)
    {
        $results = [];

        foreach ($nodes as $node) {
            $results[] = $node;
        }

        parent::__construct($results);
    }
}