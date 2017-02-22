<?php

namespace Stoatally\DocumentObjectModel;

use DomNode;

interface ImportableNode
{
    public function getImportableNode(): DomNode;
}