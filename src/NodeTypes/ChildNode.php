<?php

namespace Stoatally\Dom\NodeTypes;

interface ChildNode
{
    public function getParent(): Node;

    public function hasParent(): bool;

    public function appendSibling($value): ChildNode;

    public function prependSibling($value): ChildNode;

    public function remove(): ChildNode;

    public function replaceWith($value): ChildNode;

    public function wrapWith($value): ChildNode;
}