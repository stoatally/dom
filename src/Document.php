<?php

namespace Stoatally\Dom;

use DomDocument;
use DomNode;
use DomXPath;

class Document extends DomDocument implements Node, ImportableNode, QueryableNode
{
    use NodeTrait;
    use QueryableNodeTrait;

    private $xpath;

    public function getXPath(): DomXPath
    {
        return $this->xpath;
    }

    public function setXPath(DomXPath $xpath)
    {
        $this->xpath = $xpath;
    }

    public function getDocument(): DomDocument
    {
        return $this;
    }

    public function getImportableNode(): DomNode
    {
        $fragment = $this->createDocumentFragment();

        foreach ($this->childNodes as $node) {
            $fragment->appendChild($node->cloneNode(true));
        }

        return $fragment;
    }
}