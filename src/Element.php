<?php

namespace Stoatally\Dom;

use DomElement;

class Element extends DomElement implements Node, SiblingNode, QueryableNode
{
    use NodeTrait;
    use SiblingNodeTrait;
    use QueryableNodeTrait;
}