<?php

namespace Stoatally\Dom\NodeTypes;

interface Node
{
    public function getDocument(): Document;

    public function getNode(): Node;

    public function import($value): Node;

    public function append($value): Node;

    public function prepend($value): Node;

    public function getContents(): ?string;

    public function setContents($value): Node;

    /**
     * Duplicate a node a given number of times.
     */
    public function duplicateNode(int $times): Iterator;

    /**
     * Extract a node from the dom, leaving any children behind.
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