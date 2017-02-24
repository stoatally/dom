<?php

namespace Stoatally\Dom\NodeTypes;

use DomDocument;
use DomNode;
use DomXPath;

interface Document extends Node, ImportableNode, QueryableNode
{
    public function getXPath(): DomXPath;

    public function setXPath(DomXPath $xpath);
}