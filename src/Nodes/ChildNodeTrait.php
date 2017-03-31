<?php

namespace Stoatally\Dom\Nodes;

use Stoatally\Dom\NodeTypes;

trait ChildNodeTrait
{
    public function appendSibling($value): NodeTypes\ChildNode
    {
        $node = $this->import($value);

        if (isset($this->nextSibling)) {
            return $this->parentNode->insertBefore($node, $this->nextSibling);
        }

        return $this->parentNode->appendChild($node);
    }

    public function prependSibling($value): NodeTypes\ChildNode
    {
        return $this->parentNode->insertBefore($this->import($value), $this);
    }

    public function replaceNode($value): NodeTypes\ChildNode
    {
        return $this->parentNode->replaceChild($this->import($value), $this);
    }

    public function wrapNode($value): NodeTypes\ChildNode
    {
        $parent = $this->prependSibling($value);
        $parent->appendChild($this);

        return $parent;
    }
}