<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;

interface ImportableNode
{
    public function getImportableNode(): DomNode;
}