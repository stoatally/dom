<?php

namespace Stoatally\Dom\Nodes;

use DomElement;
use Stoatally\Dom\NodeTypes;

class Element extends DomElement implements NodeTypes\Element
{
    use NodeTrait;
    use ChildNodeTrait;
    use QueryableNodeTrait;
}