<?php

namespace Stoatally\Dom\NodeTypes;

interface ChildNode
{
    /**
     * Insert a node or text string immediately after this node.
     */
    public function appendSibling($value): ChildNode;

    /**
     * Insert a node or text string immediately before this node.
     */
    public function prependSibling($value): ChildNode;

    /**
     * Replace this node with another node or text string.
     */
    public function replaceNode($value): ChildNode;

    /**
     * Wrap this node with another node.
     */
    public function wrapNode($value): ChildNode;
}
