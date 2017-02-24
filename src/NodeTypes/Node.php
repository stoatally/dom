<?php

namespace Stoatally\Dom\NodeTypes;

interface Node {
    public function getDocument(): Document;

    public function getNode(): Node;

    public function import($value): Node;

    public function set($value): Node;

    public function get(): ?string;

    public function append($value): Node;

    public function prepend($value): Node;

    public function duplicate(int $times): Iterator;

    public function repeat($items, ?Callable $callback = null): Iterator;
}