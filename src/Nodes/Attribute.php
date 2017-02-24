<?php

namespace Stoatally\Dom\Nodes;

use DomAttr;
use DomNode;
use Stoatally\Dom\NodeTypes;

class Attribute implements NodeTypes\Attribute
{
    use NodeTypes\LibxmlNodeTrait;
    use NodeTypes\NamedNodeTrait;

    public function __construct(DomAttr $libxml)
    {
        $this->setLibxml($libxml);
    }
}