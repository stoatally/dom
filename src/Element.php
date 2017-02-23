<?php

namespace Stoatally\Dom;

use DomElement;

class Element extends DomElement implements Node, QueryableNode
{
    use NodeTrait;
    use QueryableNodeTrait;
}