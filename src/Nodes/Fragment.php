<?php

namespace Stoatally\Dom\Nodes;

use DomDocumentFragment;
use DomNode;
use Stoatally\Dom\NodeTypes;

class Fragment implements NodeTypes\Fragment
{
    use NodeTypes\ChildNodeTrait;
    use NodeTypes\LibxmlNodeTrait;
    use NodeTypes\NodeTrait;
    use NodeTypes\QueryableNodeTrait;

    public function __construct(DomDocumentFragment $libxml)
    {
        $this->setLibxml($libxml);
    }

    public function appendXml(string $xml)
    {
        return $this->libxml->appendXml($xml);
    }
}