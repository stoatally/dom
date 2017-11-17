<?php

namespace Stoatally\Dom\NodeTypes;

interface Node
{
    /**
     * Get the document that the node belongs to.
     */
    public function getDocument(): Document;

    /**
     * Return the current node.
     */
    public function getNode(): Node;

    /**
     * Import a node or a text string.
     */
    public function import($value): Node;

    /**
     * Insert a node or text string as the last child of this node.
     */
    public function append($value): Node;

    /**
     * Insert a node or text string as the first child of this node.
     */
    public function prepend($value): Node;

    /**
     * Get the text string of this node.
     */
    public function getContents(): ?string;

    /**
     * Set the contents of this node to a node or a text string.
     */
    public function setContents($value): Node;

    /**
     * Duplicate a node a given number of times.
     */
    public function duplicateNode(int $times): Iterator;

    /**
     * Extract a node from the dom, leaving its children in place.
     */
    public function extractNode(): Iterator;

    /**
     * Remove a node from the dom.
     */
    public function removeNode(): Node;

    /**
     * Repeat a node for every item in an array or iteratable, and set its contents.
     */
    public function repeatNode($items, ?Callable $callback = null): Iterator;
}