<?php

namespace Stoatally\Dom\NodeTypes;

interface QueryableNode
{
    public function select(string $query): Iterator;
}