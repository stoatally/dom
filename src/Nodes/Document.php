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

    public function getDocument(): NodeTypes\Document
    {
        return $this;
    }

    public function getDocumentElement(): NodeTypes\Element
    {
        return $this->documentElement;
    }

    public function getImportableNode(): NodeTypes\Node
    {
        $fragment = $this->createDocumentFragment();

        foreach ($this->getChildren() as $node) {
            $fragment->appendChild($node->cloneNode(true));
        }

        return $fragment;
    }
}