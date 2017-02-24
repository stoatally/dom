<?php

namespace Stoatally\Dom\NodeTypes;

interface ParentNode
{
    public function getChildren(): Iterator;

    public function set($value): Node;

    public function get(): ?string;

    public function append($value): Node;

    public function prepend($value): Node;
}