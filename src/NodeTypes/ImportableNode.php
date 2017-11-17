<?php

namespace Stoatally\Dom\NodeTypes;

interface ImportableNode
{
    /**
     * Collapse an iterator into a single node that can be imported.
     */
    public function getImportableNode(): Node;
}