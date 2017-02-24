<?php

namespace Stoatally\Dom\Nodes;

use DomDocumentFragment;
use Stoatally\Dom\NodeTypes;

class Fragment extends DomDocumentFragment implements NodeTypes\Fragment
{
    use NodeTypes\NodeTrait;
    use NodeTypes\ChildNodeTrait;
    use NodeTypes\QueryableNodeTrait;
}