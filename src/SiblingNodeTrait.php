<?php

namespace Stoatally\Dom;

use DomDocument;
use DomNode;

trait SiblingNodeTrait {
    public function after($value): DomNode
    {
        $node = $this->import($value);

        if (isset($this->nextSibling)) {
            return $this->parentNode->insertBefore($node, $this->nextSibling);
        }

        return $this->parentNode->appendChild($node);
    }

    public function before($value): DomNode
    {
        return $this->parentNode->insertBefore($this->import($value), $this);
    }

    public function replace($value): DomNode
    {
        return $this->parentNode->replaceChild($this->import($value), $this);
    }

    public function wrap($value): DomNode
    {
        $parent = $this->before($value);
        $parent->appendChild($this);

        return $parent;
    }
}