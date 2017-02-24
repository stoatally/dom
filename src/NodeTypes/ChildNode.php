<?php

namespace Stoatally\Dom\NodeTypes;

interface ChildNode
{
    public function getParent(): Node;

    public function hasParent(): bool;

    public function after($value): ChildNode;

    public function before($value): ChildNode;

    public function remove(): ChildNode;

    public function replace($value): ChildNode;

    public function wrap($value): ChildNode;
}