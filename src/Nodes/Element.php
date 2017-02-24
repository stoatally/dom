<?php

namespace Stoatally\Dom\Nodes;

use DomElement;
use DomNode;
use Stoatally\Dom\NodeTypes;

class Element implements NodeTypes\Element
{
    use NodeTypes\ChildNodeTrait;
    use NodeTypes\LibxmlNodeTrait;
    use NodeTypes\NodeTrait;
    use NodeTypes\NamedNodeTrait;
    use NodeTypes\QueryableNodeTrait;

    public function __construct(DomElement $libxml)
    {
        $this->setLibxml($libxml);
    }
}