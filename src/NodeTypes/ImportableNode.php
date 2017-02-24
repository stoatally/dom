<?php

namespace Stoatally\Dom\NodeTypes;

interface ImportableNode
{
    public function getImportableNode(): Node;
}