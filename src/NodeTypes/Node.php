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

    public function duplicateNode(int $times): Iterator;

    public function repeatNode($items, ?Callable $callback = null): Iterator;
}