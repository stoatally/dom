<?php

namespace Stoatally\Dom\NodeTypes;

interface ChildIterator extends ChildNode
{
    /**
     * Insert a node between each of the nodes in an iterator.
     *
     * @param   string|ChildNode|ImportableNode     $value
     *  The node to insert between the existing nodes.
     */
    public function betweenSiblings($value): ChildNode;
}