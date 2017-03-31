<?php

namespace Stoatally\Dom\Nodes;

use DomText;
use Stoatally\Dom\NodeTypes;

class Text extends DomText implements NodeTypes\Text
{
    use NodeTrait;
    use ChildNodeTrait;
    use QueryableNodeTrait;
}