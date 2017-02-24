<?php

namespace Stoatally\Dom\NodeTypes;

use DomXPath;

interface Document extends Node, ImportableNode, QueryableNode
{
    public function getDocumentElement(): Element;

    public function getXPath(): DomXPath;

    public function setXPath(DomXPath $xpath);
}