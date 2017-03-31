<?php

namespace Stoatally\Dom\Nodes;

use Stoatally\Dom\NodeTypes;

trait ChildNodeTrait {
    public function after($value): NodeTypes\ChildNode
    {
        $node = $this->import($value);

        if (isset($this->nextSibling)) {
            return $this->parentNode->insertBefore($node, $this->nextSibling);
        }

        return $this->parentNode->appendChild($node);
    }

    public function before($value): NodeTypes\ChildNode
    {
        return $this->parentNode->insertBefore($this->import($value), $this);
    }

    public function replace($value): NodeTypes\ChildNode
    {
        return $this->parentNode->replaceChild($this->import($value), $this);
    }

    public function wrap($value): NodeTypes\ChildNode
    {
        $parent = $this->before($value);
        $parent->appendChild($this);

        return $parent;
    }
}