<?php

namespace Stoatally\Dom\NodeTypes;

interface Text extends LibxmlNode, ChildNode, Node, QueryableNode
{
    public function getContent(): string;

    public function setContent(string $value);
}