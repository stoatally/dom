<?php

namespace Stoatally\Dom;

use DomNode;

interface ImportableNode
{
    public function getImportableNode(): DomNode;
}