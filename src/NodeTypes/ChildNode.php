<?php

namespace Stoatally\Dom\NodeTypes;

interface ChildNode
{
    public function appendSibling($value): ChildNode;

    public function prependSibling($value): ChildNode;

    public function replaceNode($value): ChildNode;

    public function wrapNode($value): ChildNode;
}
