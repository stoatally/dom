<?php

namespace Stoatally\Dom\Nodes;

use DomDocument;
use DomNode;
use DomXPath;
use Stoatally\Dom\NodeTypes;

class Document extends DomDocument implements NodeTypes\Document
{
    use NodeTypes\NodeTrait;
    use NodeTypes\QueryableNodeTrait;

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