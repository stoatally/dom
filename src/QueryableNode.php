<?php

namespace Stoatally\Dom;

interface QueryableNode
{
    public function select(string $query): Iterator;
}