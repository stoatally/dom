<?php

namespace Stoatally\Dom\Nodes;

use DomDocument;
use DomNode;
use DomXPath;
use Stoatally\Dom\NodeTypes;

class Document implements NodeTypes\Document
{
    use NodeTypes\DocumentTrait;
    use NodeTypes\LibxmlNodeTrait;
    use NodeTypes\NodeTrait;
    use NodeTypes\ParentNodeTrait;
    use NodeTypes\QueryableNodeTrait;

    public function __construct(DomDocument $libxml)
    {
        $this->setLibxml($libxml);
    }

    public function getDocument(): NodeTypes\Document
    {
        return $this;
    }
}