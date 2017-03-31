<?php

namespace Stoatally\Dom\Nodes;

use DomDocumentFragment;
use Stoatally\Dom\NodeTypes;

class Fragment extends DomDocumentFragment implements NodeTypes\Fragment
{
    use NodeTrait;
    use ChildNodeTrait;
    use QueryableNodeTrait;
}