<?php

namespace Stoatally\Dom\NodeTypes;

use DomDocument;
use DomNode;

interface Node {
    public function getDocument(): DomDocument;

    public function getNode(): DomNode;

    public function import($value): Node;

    public function set($value): DomNode;

    public function get(): ?string;

    public function append($value): DomNode;

    public function prepend($value): DomNode;

    public function duplicate(int $times): Iterator;

    public function repeat($items, ?Callable $callback = null): Iterator;
}